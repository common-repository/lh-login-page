<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form name="lh_login_page-backend_form" method="post" action="">
<?php wp_nonce_field( $this->namespace."-backend_nonce", $this->namespace."-backend_nonce", false ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->page_id_field; ?>"><?php _e("Login Page ID;", $this->namespace ); ?></label></th>
<td><input type="number" name="<?php echo $this->page_id_field; ?>" id="<?php echo $this->page_id_field; ?>" value="<?php echo $this->options[ $this->page_id_field ]; ?>" size="10" /><a href="<?php echo get_permalink($this->options[ $this->page_id_field ]); ?>">Link</a></td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->use_email_field_name; ?>"><?php _e("Use email addresses:", $this->namespace ); ?></label></th>
<td><select name="<?php echo $this->use_email_field_name; ?>" id="<?php echo $this->use_email_field_name; ?>">'
<option value="1" <?php if ($this->options[$this->use_email_field_name] == 1){ echo 'selected="selected"'; } ?> >Yes</option>
<option value="0" <?php if ($this->options[$this->use_email_field_name] == 0){ echo 'selected="selected"'; } ?> >No</option>
</select> - <?php  _e("Set this to yes if you want too use email addresses instead of usernames to log in.", $this->namespace );  ?></td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->allow_redirects_field_name; ?>"><?php  _e("Allow Redirects:", $this->namespace );  ?></label></th>
<td><select name="<?php echo $this->allow_redirects_field_name; ?>" id="<?php echo $this->allow_redirects_field_name; ?>">
<option value="1" <?php if ($this->options[$this->allow_redirects_field_name] == 1){ echo 'selected="selected"'; } ?> >Yes</option>
<option value="0" <?php if ($this->options[$this->allow_redirects_field_name] == 0){ echo 'selected="selected"'; } ?> >No</option>
</select> - <?php  _e("Set this to yes if you want too allow other plugins to filter login redirects.", $this->namespace );  ?></td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->appmode_prompt_logon_field_name; ?>"><?php  _e("Prompt logon in app mode:", $this->namespace );  ?></label></th>
<td><select name="<?php echo $this->appmode_prompt_logon_field_name; ?>" id="<?php echo $this->appmode_prompt_logon_field_name; ?>">
<option value="1" <?php if ($this->options[$this->appmode_prompt_logon_field_name] == 1){ echo 'selected="selected"'; } ?> >Yes</option>
<option value="0" <?php if ($this->options[$this->appmode_prompt_logon_field_name] == 0){ echo 'selected="selected"'; } ?> >No</option>
</select> - <?php  _e("Set this to yes if you want too redirect to the logon page if the browser is in app mode", $this->namespace );  ?></td>
</tr>
</table>
<?php submit_button( 'Save Changes' ); ?>
</form>