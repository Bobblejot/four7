<?php

class four7_Widget_Contact_Info extends WP_Widget {

	function four7_Widget_Contact_Info() {
		$widget_ops = array( 'classname' => 'contact_info', 'description' => 'company contact information' );

		$control_ops = array( 'id_base' => 'contact_info-widget' );

		$this->WP_Widget( 'contact_info-widget', 'LMC: Contact Info', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<address>
			<p class="adr">
				<?php if ( $instance['street-address'] ): ?>
					<span class="street-address"><?php echo $instance['street-address']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['locality'] ): ?>
					<span class="locality"><?php echo $instance['locality']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['region'] ): ?>
					<span class="region"><?php echo $instance['region']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['postal-code'] ): ?>
					<span class="postal-code"><?php echo $instance['postal-code']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['country-name'] ): ?>
					<span class="country-name"><?php echo $instance['country-name']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['phone'] ): ?>
					<span class="phone"><?php _e( 'Phone:', 'four7' ); ?> <?php echo $instance['phone']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['fax'] ): ?>
					<span class="fax"><?php _e( 'Fax:', 'four7' ); ?> <?php echo $instance['fax']; ?></span><br>
				<?php endif; ?>

				<?php if ( $instance['email'] ): ?>
					<span class="email"><?php _e( 'Email:', 'four7' ); ?>
						<a href="mailto:<?php echo $instance['email']; ?>"><?php if ( $instance['emailtxt'] ) {
								echo $instance['emailtxt'];
							} else {
								echo $instance['email'];
							} ?></a></span><br>
				<?php endif; ?>

				<?php if ( $instance['web'] ): ?>
					<span class="web"><?php _e( 'Web:', 'four7' ); ?>
						<a href="<?php echo $instance['web']; ?>"><?php if ( $instance['webtxt'] ) {
								echo $instance['webtxt'];
							} else {
								echo $instance['web'];
							} ?></a></span><br>
				<?php endif; ?>
			</p></address>
		<?php
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']          = $new_instance['title'];
		$instance['street-address'] = $new_instance['street-address'];
		$instance['locality']       = $new_instance['locality'];
		$instance['region']         = $new_instance['region'];
		$instance['postal-code']    = $new_instance['postal-code'];
		$instance['country-name']   = $new_instance['country-name'];
		$instance['phone']          = $new_instance['phone'];
		$instance['fax']            = $new_instance['fax'];
		$instance['email']          = $new_instance['email'];
		$instance['emailtxt']       = $new_instance['emailtxt'];
		$instance['web']            = $new_instance['web'];
		$instance['webtxt']         = $new_instance['webtxt'];

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => 'Contact Info' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'street-address' ); ?>">street-address:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'street-address' ); ?>" name="<?php echo $this->get_field_name( 'street-address' ); ?>" value="<?php echo $instance['street-address']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'locality' ); ?>">locality:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'locality' ); ?>" name="<?php echo $this->get_field_name( 'locality' ); ?>" value="<?php echo $instance['locality']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'region' ); ?>">region:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'region' ); ?>" name="<?php echo $this->get_field_name( 'region' ); ?>" value="<?php echo $instance['region']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'postal-code' ); ?>">postal-code:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'postal-code' ); ?>" name="<?php echo $this->get_field_name( 'postal-code' ); ?>" value="<?php echo $instance['postal-code']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'country-name' ); ?>">country-name:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'country-name' ); ?>" name="<?php echo $this->get_field_name( 'country-name' ); ?>" value="<?php echo $instance['country-name']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>">Phone:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fax' ); ?>">Fax:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" value="<?php echo $instance['fax']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>">Email:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'emailtxt' ); ?>">Email Link Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'emailtxt' ); ?>" name="<?php echo $this->get_field_name( 'emailtxt' ); ?>" value="<?php echo $instance['emailtxt']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'web' ); ?>">Website URL (with HTTP):</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'web' ); ?>" name="<?php echo $this->get_field_name( 'web' ); ?>" value="<?php echo $instance['web']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'webtxt' ); ?>">Website URL Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id( 'webtxt' ); ?>" name="<?php echo $this->get_field_name( 'webtxt' ); ?>" value="<?php echo $instance['webtxt']; ?>" />
		</p>
	<?php
	}
}

?>