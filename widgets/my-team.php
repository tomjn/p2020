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
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'p2020' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
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

		echo $theme_settings['before_widget'];

		// TODO Do we want caching
		// $my_team = get_transient( $this->id . '-myteam-widget-data' );

		if ( empty( $my_team ) ) {
			$my_team = $this->get_team_members();
			// 	set_transient( $this->id . '-myteam-widget-data', $my_team, self::$expiration );
		}

		$count = count( $my_team );
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

		if ( ! empty( $my_team ) ) {
			echo "<div class='widgets-multi-column-grid'><ul>";

			foreach ( $my_team as $member ) {
				echo "<li>{$member}</li>";
			}

			if ( current_user_can( 'administrator' ) ) {
				$manage_team_link = 'https://wordpress.com/people/team/' .
					\WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );
				echo '<li id="widget-myteam-manage-icon">' .
					'<a href="' . esc_url( $manage_team_link ) . '">+</a>' . 
					'</li>';
			}

			echo '</ul></div>';
		}

		echo $theme_settings['after_widget'];
		stats_extra( 'widget_view', 'p2020-my-team-widget' );
	}

	function get_team_members( ) {
		global $blog_id;
		$team_members = [];
		$blog_users = get_users( [ 'blog_id' => $blog_id ] );

		foreach ( $blog_users as $user ) {
			$team_members[$user->data->user_email] =
				'<a href="' . esc_url( $user->data->user_url ) . '">' .
					get_avatar( $user->ID, 48, '', '', [ 'force_display' => true ] ) .
				'</a>';
		}

		return $team_members;
	}

	function extend_default_options( $options = [] ) {
		$defaults = [
			'title' => $this->default_title
		];

		$merged = array_merge( $defaults, $options );

		return $merged;
	}
}

function my_team_widget_init() {
	register_widget( __NAMESPACE__ . '\My_Team_Widget' );
}

