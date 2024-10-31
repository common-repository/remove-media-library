<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://webstilo.com
 * @since      1.0.0
 *
 * @package    remove-media-library
 * @subpackage remove-media-library
 */
?>

<?php

	/**
	 * The form to be loaded on the plugin's admin page
	 */

	// Generate a custom nonce value.
	$nonce = wp_create_nonce( 'form_nonce' ); 

?>
<DIV class="wrap">
    <div id="icon-my-id" class="icon32">
        <br />
    </div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <H3>
                <?php echo esc_attr__('Removes files from WordPress Media Library fast and easy',  $this->plugin_name) . '</H3>';
    echo '<p><B>' . esc_attr__('CAUTION:',  $this->plugin_name) . '</B> ' . esc_attr__('Orphan files are files not used on posts, pages nor WooCommerce products, BUT may be used in other parts of your website such as the site logo, widgets or sliders',  $this->plugin_name). '</p>';
    ?>
                <div id="post-body-content">


                    <DIV class="postbox">
                        <DIV class="inside">
                            <p>
                                <?php if (defined('MEDIA_TRASH') && MEDIA_TRASH){
        echo esc_attr__('WP Media trash is',  $this->plugin_name) .  ' <B>' . esc_attr__('ENABLED',  $this->plugin_name) . '</B>. ' . esc_attr__('Media files will go to the trash. If necessary, you can recover them from there.',  $this->plugin_name);
        $button_label = esc_attr__('Disable Media Trash', $this->plugin_name);
        $value = '0';
}
else{
    echo '<B>' . esc_attr__('WARNING!',  $this->plugin_name) . '</B> ' . esc_attr__('WP Media trash is',  $this->plugin_name) . ' <B style="color:red;"> ' . esc_attr__('DISABLED',  $this->plugin_name) . '</B>. ' . esc_attr__('Your files will be deleted PERMANENTLY. Please make a full backup beforehand.',  $this->plugin_name);
    $button_label = esc_attr__('Enable Media Trash', $this->plugin_name);
    $value = '1';
}
?>
                            </p>
                            <!-- Build the Form -->
                            <form action="<?php echo esc_url( admin_url('admin-post.php')); ?>" method="post">
                                <input type="hidden" name="action" value="activate_media_trash">
                                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>" />
                                <input type="hidden" id="<?php echo $this->plugin_name; ?>-action"
                                    name="<?php echo $this->plugin_name; ?> [action]" value="<?php echo $value; ?>" />

                                <input type="submit" value="<?php echo $button_label; ?>" class="button button-primary">
                            </form>
                        </DIV>
                    </DIV>

                    <BR>


                    <!-- MAIN FORM -->

                    <div class="form">

                        <form action="#" method="post" id="form1">

                            <input type="hidden" name="action" value="form_response">
                            <input type="hidden" name="nonce" value="<?php echo $nonce ?>" />
                            <div class="checkbox-group1 required">
                                <fieldset>
                                    <legend><span>
                                            <?php esc_attr_e('1- Select media type',$this->plugin_name); ?></span></legend>
                                    <?php $count1 = $this->attachment_count('image', false);
                   $count2 = $this->attachment_count('image', true); ?>
                                    <div>
                                        <label for="<?php echo $this->plugin_name; ?>-images">
                                            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-images"
                                                name="<?php echo $this->plugin_name; ?> [images]" value="1" <?php
                                                if($count1==0 && $count2==0) echo 'disabled' ;?>/>
                                            <?php echo number_format_i18n( $count1, 0 ) . esc_attr__(' images (', $this->plugin_name) . number_format_i18n( $count2, 0 ) . esc_attr__(' orphans)', $this->plugin_name); ?>
                                        </label>
                                    </div>

                                    <div>
                                        <?php $count1 = $this->attachment_count('video', false);
                   $count2 = $this->attachment_count('video', true); ?>
                                        <label for="<?php echo $this->plugin_name; ?>-videos">
                                            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-videos"
                                                name="<?php echo $this->plugin_name; ?> [videos]" value="1" <?php
                                                if($count1==0 && $count2==0) echo 'disabled' ;?>/>
                                            <?php echo number_format_i18n( $count1, 0 ) . esc_attr__(' videos (', $this->plugin_name) . number_format_i18n( $count2, 0 ) . esc_attr__(' orphans)', $this->plugin_name); ?>
                                        </label>
                                    </div>

                                    <div>
                                        <?php $count1 = $this->attachment_count('application', false);
                   $count2 = $this->attachment_count('application', true); ?>
                                        <label for="<?php echo $this->plugin_name; ?>-docs">
                                            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-docs"
                                                name="<?php echo $this->plugin_name; ?> [docs]" value="1" <?php
                                                if($count1==0 && $count2==0) echo 'disabled' ;?>/>
                                            <?php echo number_format_i18n( $count1, 0 ) . esc_attr__(' documents (', $this->plugin_name) . number_format_i18n( $count2, 0 ) . esc_attr__(' orphans)', $this->plugin_name); ?>
                                        </label>
                                    </div>

                                    <div>
                                        <?php $count1 = $this->attachment_count('audio', false);
                   $count2 = $this->attachment_count('audio', true); ?>
                                        <label for="<?php echo $this->plugin_name; ?>-audios">
                                            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-audios"
                                                name="<?php echo $this->plugin_name; ?> [audios]" value="1" <?php
                                                if($count1==0 && $count2==0) echo 'disabled' ;?>/>
                                            <?php echo number_format_i18n( $count1, 0 ) . esc_attr__(' audios (', $this->plugin_name) . number_format_i18n( $count2, 0 ) . esc_attr__(' orphans)', $this->plugin_name); ?>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>



                            <div id="errorContainer1" class="errorContainer">
                                <p>
                                    <?php esc_attr_e('Please select at least one media type', $this->plugin_name);?>
                                </p>
                            </div>

                            <BR>
                            <div class="checkbox-group2 required">
                                <fieldset>
                                    <legend><span>
                                            <?php esc_attr_e('2- Remove ALL or only ORPHANS?',$this->plugin_name);?></span>
                                    </legend>
                                    <label for="<?php echo $this->plugin_name; ?>-orphans1">
                                        <input type="radio" id="<?php echo $this->plugin_name; ?>-orphans1"
                                            name="<?php echo $this->plugin_name; ?> [orphans]" value="1" />
                                        <?php esc_attr_e('Remove ONLY orphan selected files', $this->plugin_name); ?>
                                    </label>

                                    <label for="<?php echo $this->plugin_name; ?>-orphans2">
                                        <input type="radio" id="<?php echo $this->plugin_name; ?>-orphans2"
                                            name="<?php echo $this->plugin_name; ?> [orphans]" value="2" />
                                        <?php esc_attr_e('Remove ALL selected files', $this->plugin_name); ?>
                                    </label>


                                </fieldset>
                            </div>

                            <div id="errorContainer2" class="errorContainer">
                                <p>
                                    <?php esc_attr_e('Please select REMOVE action', $this->plugin_name);?>
                                </p>
                            </div>

                            <p class="submit"><input type="submit" name="submit" id="submit"
                                    class="button button-primary"
                                    value="<?php esc_attr_e('REMOVE SELECTED FILES!', $this->plugin_name)?>"></p>

                            <div id="errorContainer3" class="errorContainer">
                                <p>
                                    <?php esc_attr_e('Nothing to do', $this->plugin_name);?>
                                </p>
                            </div>

                            <div id="fountainG">
                                <div id="fountainG_1" class="fountainG"></div>
                                <div id="fountainG_2" class="fountainG"></div>
                                <div id="fountainG_3" class="fountainG"></div>
                                <div id="fountainG_4" class="fountainG"></div>
                                <div id="fountainG_5" class="fountainG"></div>
                                <div id="fountainG_6" class="fountainG"></div>
                                <div id="fountainG_7" class="fountainG"></div>
                                <div id="fountainG_8" class="fountainG"></div>
                            </div>
                        </form>
                    </div>
                    <br />
                    <?php $file = plugin_dir_path( __FILE__ ).'../result.log';
    if (file_exists($file) && filesize($file) > 0){
        echo  '<B>' . esc_attr__('LAST OUTPUT:', $this->plugin_name) . '</B>';
        echo '<div id="form_feedback">';
        echo file_get_contents($file);
        echo '</div>';
    } ?>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox"
                        style="background: #ffc; border: 1px solid #333; margin: 2px; padding: 3px 15px">
                        <div class="inside">
                            <p>Plugin developed by:</p>
                            <a href="https://webstilo.com">WebStilo.com</a>
                        </div>
                    </div>

                </div>

        </div>


    </div>
</div>
<?php   