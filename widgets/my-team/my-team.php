<?php

namespace P2020;

class My_Team_Widget extends \WP_Widget {
	private $default_title;

	private $exclude_users = [
		26957695, // a8cuser
	];

	/**
	 * Registers the widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'p2020-my-team-widget', // Base ID
			__( 'P2 Team', 'p2020' ), // Name
			[
				'description'                 => __( 'Allows you to showcase your team members.', 'p2020' ),
				'customize_selective_refresh' => true,
			]
		);

		if ( is_active_widget( false, false, $this->id_base ) || is_active_widget( false, false, 'monster' ) || is_customize_preview() ) {
			add_action( 'wp_print_styles', [ $this, 'enqueue_style' ] );
		}

		$this->default_title = __( 'Team', 'p2020' );
	}

	public function enqueue_style() {
		wp_enqueue_style( 'widget-grid-and-list' );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$instance = $this->extend_default_options( $instance );

		$title = $instance['title'];
		if ( false === $title ) {
			$title = $this->default_title;
		}

		$limit = (int) $instance['limit'];

		global $wp_roles;
		$all_roles      = array_keys( $wp_roles->roles );
		$selected_roles = $instance['roles'] ?? $all_roles;
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'p2020' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label><?php esc_html_e( 'Limit no. of team members displayed to:', 'p2020' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
		</p>

		<p>
			<label><?php esc_html_e( 'Display team members with role:', 'p2020' ); ?></label>
			<ul>
			<?php foreach ( $all_roles as $role ) { ?>
				<li>
					<label for="<?php echo esc_attr( $this->get_field_id( 'role_' . $role ) ); ?>">
					<input type="checkbox" class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'role_' . $role ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'roles[]' ) ); ?>"
						value="<?php echo esc_attr( $role ); ?>" <?php echo in_array( $role, $selected_roles, true ) ? 'checked' : ''; ?> />
					<?php echo esc_html( translate_user_role( ucfirst( $role ), 'p2020' ) ); ?>
				</label>
				</li>
			<?php } ?>
			</ul>
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
	// Reason: -- Function signature for WP_Widget::update()
	// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
	// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed
	public function update( $new_instance, $old_instance ) {
		$instance          = [];
		$instance['title'] = wp_kses( $new_instance['title'], [] );

		$instance['limit'] = (int) $new_instance['limit'];
		if ( $instance['limit'] < 1 ) {
			$instance['limit'] = 14;
		}

		global $wp_roles;
		$all_roles = array_keys( $wp_roles->roles );
		// Make sure submitted roles are valid
		$instance['roles'] = array_intersect( $all_roles, $new_instance['roles'] );

		return $instance;
	}
	// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
	// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed

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
	public function widget( $args, $instance ) {
		$instance = $this->extend_default_options( $instance );

		$title = $instance['title'];

		$title = apply_filters( 'widget_title', $title );

		$limit = (int) $instance['limit'];

		$roles = $instance['roles'];

		// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
		echo $args['before_widget'];

		$my_team = $this->get_team_info( $limit, $roles );

		if ( ! empty( $title ) ) {
			$widget_title = $args['before_title'] . $title;

			if ( is_user_member_of_blog() && current_user_can( 'administrator' ) ) {
				$manage_team_url   = admin_url( 'users.php' );
				$invite_people_url = admin_url( 'user-new.php' );
				$myteam_menu       = new \P2020\EllipsisMenu();
				$myteam_menu->add_item( __( 'Manage team', 'p2020' ), esc_url( $manage_team_url ) );
				$myteam_menu->add_item( __( 'Invite people', 'p2020' ), esc_url( $invite_people_url ) );
				$widget_title .= $myteam_menu->generate();
			}

			$widget_title .= $args['after_title'];

			// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
			echo $widget_title;
		}

		$my_team_members = $my_team['members'];
		if ( ! empty( $my_team_members ) ) {
			?>
			<div class='widgets-multi-column-grid'><ul>

			<?php foreach ( $my_team_members as $member ) { ?>
				<?php
					$avatar_link_text = sprintf(
						/* translators: %1$s is replaced with the user's display name; %2$s is the user's nicename */
						__( 'Posts by %1$s ( @%2$s )', 'p2020' ),
						$member->data->display_name,
						$member->data->user_nicename
					);
				?>
				<li>
					<a
						href="<?php echo esc_url( get_blog_url( '/author/' . $member->data->user_nicename ) ); ?>"
						class="widget-myteam-item"
					>
						<?php echo get_avatar( $member->ID, 40, '', '', [ 'force_display' => true ] ); ?>
						<span class="screen-reader-text">
							<?php echo esc_html( $avatar_link_text ); ?>
						</span>
					</a>
				</li>
				<?php
			}

			$hidden_count = $my_team['size'] - count( $my_team_members );
			if ( is_user_member_of_blog() && current_user_can( 'administrator' ) ) {
				$manage_team_link = admin_url( 'users.php' );
				?>
				<li class="widget-myteam-manage-icon">
					<a
						href="<?php echo esc_url( $manage_team_link ); ?>"
						class="widget-myteam-item"
						aria-label="<?php esc_attr_e( 'Invite people', 'p2020' ); ?>"
						data-tippy-content="<?php esc_attr_e( 'Invite people', 'p2020' ); ?>"
					>
						<?php if ( $hidden_count > 0 ) : ?>
							+<?php echo esc_html( $hidden_count ); ?>
						<?php else : ?>
							<span class="widget-myteam-manage-icon-plus"></span>
						<?php endif; ?>
					</a>
				</li>
				<?php
			} else {
				if ( $hidden_count > 0 ) :
					?>
					<li class="widget-myteam-manage-icon">
						<span class="widget-myteam-item">+<?php echo esc_html( $hidden_count ); ?></span>
					</li>
				<?php endif; ?>
			<?php } ?>

			</ul></div>
			<?php
		}

		// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
		echo $args['after_widget'];
		if ( function_exists( 'stats_extra' ) ) {
			stats_extra( 'widget_view', 'p2020-my-team-widget' );
		}
	}

	/**
	 * Get the team info
	 *
	 * @param integer|null $limit
	 * @param array|null $roles
	 * @return array
	 */
	protected function get_team_info( ?int $limit = null, ?array $roles = null ): array {
		global $blog_id;
		$team_info = [];

		if ( empty( $roles ) ) {
			return [
				'members' => [],
				'size'    => 0,
			];
		}

		$user_query_param = [
			'blog_id'  => $blog_id,
			'role__in' => $roles,
			'exclude'  => array_merge(
				[ get_current_user_id() ],
				$this->exclude_users
			),
		];

		if ( ! empty( $limit ) ) {
			$user_query_param['number'] = $limit;
		}

		$users_data        = $this->get_users_data( $user_query_param );
		$team_info['size'] = $users_data['total'];
		$users             = $users_data['users'];

		// Check if current user is part of team
		// If yes, place in front.
		$me = get_users(
			[
				'blog_id'  => $blog_id,
				'role__in' => $roles,
				'include'  => [ get_current_user_id() ],
			]
		);
		if ( ! empty( $me ) ) {
			$users              = array_merge( $me, array_slice( $users, 0, $limit - 1 ) );
			$team_info['size'] += 1;
		}

		$team_info['members'] = $users;

		return $team_info;
	}

	/**
	 *
	 *
	 * @param array $options
	 * @return array
	 */
	protected function extend_default_options( array $options = [] ): array {
		global $wp_roles;
		$all_roles = array_keys( $wp_roles->roles );

		$defaults = [
			'title' => $this->default_title,
			'limit' => 14,
			'roles' => $all_roles,
		];

		$merged = array_merge( $defaults, $options );

		if ( $merged['limit'] < 1 ) {
			$merged['limit'] = 10;
		}

		return $merged;
	}

	private function get_users_data( array $args = [] ) : array {
		$args                = wp_parse_args( $args );
		$args['count_total'] = true;

		$user_search = new \WP_User_Query( $args );
		return [
			'total' => $user_search->get_total(),
			'users' => $user_search->get_results(),
		];
	}
}

function my_team_widget_init() {
	register_widget( __NAMESPACE__ . '\My_Team_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\my_team_widget_init' );
