<?php

namespace P2020;

class My_Team_Widget extends \WP_Widget {
	// TODO Do we want caching
	// static $expiration = 600;

	var $default_title;

	/**
	 * Registers the widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'p2020-my-team-widget', // Base ID
			__( 'My Team', 'p2020' ), // Name
			[
				'description' => __( 'A widget to showcase your team members.', 'p2020' ),
			]
		);

		if ( is_active_widget( false, false, $this->id_base ) || is_active_widget( false, false, 'monster' ) ) {
			add_action( 'wp_print_styles', [ $this, 'enqueue_style' ] );
		}

		$this->default_title = __( 'My Team', 'p2020' );
	}

	function enqueue_style() {
		wp_enqueue_style( 'widget-grid-and-list' );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		$instance = $this->extend_default_options( $instance );

		$title = $instance['title'];
		if ( false === $title ) {
			$title = $this->default_title;
		}

		$limit = (int)$instance['limit'];
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'p2020' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label><?php esc_html_e( 'Limit no. of team members displayed to:', 'p2020' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo $limit; ?>" />
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = [];
		$instance['title'] = wp_kses( $new_instance['title'], [] );

		$instance['limit'] = (int) $new_instance['limit'];
		if ( $instance['limit'] < 1 ) {
			$instance['limit'] = 14;
		}

		// delete_transient( $this->id . '-myteam-widget-data' );

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @return void
	 */
	function widget( $theme_settings, $instance ) {
		$instance = $this->extend_default_options( $instance );

		$title = $instance['title'];

		$title = apply_filters( 'widget_title', $title );

		$limit = (int)$instance['limit'];

		echo $theme_settings['before_widget'];

		// TODO Do we want caching
		// $my_team = get_transient( $this->id . '-myteam-widget-data' );

		if ( empty( $my_team ) ) {
			$my_team = $this->get_team_info( $limit );
			// 	set_transient( $this->id . '-myteam-widget-data', $my_team, self::$expiration );
		}

		$count = $my_team['size'];
		if ( ! empty( $title ) ) {
			$widget_title = $theme_settings['before_title'] . $title . '<div class="widget-myteam-team-count"> (' . $count . ')</div>';

			if ( current_user_can( 'administrator' ) ) {
				$manage_team_link = 'https://wordpress.com/people/team/' .
					\WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );
				$widget_title .= '<div id="widget-myteam-manage-link">' .
					'<a href="' . esc_url( $manage_team_link ) . '">Manage</a>' .
					'</div>';
			}

			$widget_title .=  $theme_settings['after_title'];

			echo $widget_title;
		}

		$my_team_members = $my_team['members'];
		if ( ! empty( $my_team_members ) ) { ?>
			<div class='widgets-multi-column-grid'><ul>

			<?php foreach ( $my_team_members as $member ) { ?>
				<li>
					<a href="<?php esc_url( $member->data->member_url ) ?>">
						<?php echo get_avatar( $member->ID, 48, '', '', [ 'force_display' => true ] ) ?>
					</a>
				</li>
			<?php }

			if ( current_user_can( 'administrator' ) ) {
				$hidden_count = $my_team['size'] - count( $my_team_members );
				$manage_team_link = 'https://wordpress.com/people/team/' . \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() ); ?>
				<li id="widget-myteam-manage-icon" class="<?php echo ( $hidden_count ? 'has-more' : '' ) ?>" >
					<a href="<?php esc_url( $manage_team_link ) ?>">
							+<?php echo ( $hidden_count ?: '' ) ?>	
					</a> 
				</li>
			<?php } ?>

			</ul></div>
		<?php }

		echo $theme_settings['after_widget'];
		stats_extra( 'widget_view', 'p2020-my-team-widget' );
	}

	function get_team_info( $limit = null ) {
		global $blog_id;
		$team_info = [];
		$team_members = [];

		$user_query_param = [ 'blog_id' => $blog_id ];

		if ( is_a8c_p2() ) {
			 $user_query_param[ 'role' ] = 'administrator';
		} 

		$blog_users = get_users( $user_query_param );

		$team_info['size'] = count( $blog_users );

		// Make 'me' first to be displayed
		$me_idx = array_search( get_current_user_id(), array_column( $blog_users, 'ID' ) );
		$me = $blog_users[ $me_idx ];
		unset( $blog_users[ $me_idx ] );

		// If beyond display limit, randomly select which members to return
		if ( isset( $limit ) && count( $blog_users ) > $limit - 1 ) { // Less 'me'
			shuffle( $blog_users );
			$blog_users = array_slice( $blog_users, 0, $limit - 1 );
		}

		$team_members = array_merge( [ $me ], $blog_users );
		$team_info['members'] = $team_members;

		return $team_info;
	}

	function extend_default_options( $options = [] ) {
		$defaults = [
			'title' => $this->default_title,
			'limit' => 14,
		];

		$merged = array_merge( $defaults, $options );

		if ( $merged['limit'] < 1 ) {
			$merged['limit'] = 10;
		}

		return $merged;
	}
}

function my_team_widget_init() {
	register_widget( __NAMESPACE__ . '\My_Team_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\my_team_widget_init' );
