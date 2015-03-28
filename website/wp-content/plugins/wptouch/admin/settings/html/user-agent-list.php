<?php global $wptouch_pro; ?>
<div id="user-agent-list">
	<?php if ( wptouch_get_locale() == 'fr_FR' ) { ?>
	<?php _e( 'WPtouch Pro will be active when any of the following user-agents or user-agent combinations are matched', 'wptouch-pro' ); ?> :
	<?php } else { ?>
	<?php _e( 'WPtouch Pro will be active when any of the following user-agents or user-agent combinations are matched', 'wptouch-pro' ); ?> :
	<?php } ?>

	<br />
	<span class="agents">
		<?php
			$agents = $wptouch_pro->get_supported_user_agents();
			$new_agents = array();

			foreach( $agents as $agent ) {
				if ( is_array( $agent ) && count( $agent ) > 0 ) {
					$new_agent = $agent[0];
					if ( count( $agent ) > 1 ) {
						$new_agent .= ' &amp; ' . $agent[1];
					}
					$new_agents[] = $new_agent;
				} elseif( !is_array( $agent ) ) {
					$new_agents[] = $agent;
				}
			}

			echo implode( ', ', $new_agents );
		?>
	</span>
</div>