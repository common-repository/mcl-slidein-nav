<?php
/* --------------------------------------------------------------
Plugin Name: Mcl slidein nav
Plugin URI: http://memocarilog.info/wordpress/8206
Description: This Plugin will make with Custom menu Slidein nav 
Text Domain: mcl-slidein-nav
Domain Path: /languages
Version: 1.0.5
Author: Saori Miyazaki
Author URI: http://memocarilog.info/
License: GPL2
-------------------------------------------------------------- */
/*  
Copyright 2015 Saori Miyazaki ( email : saomocari@gmail.com )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */

/* -----------------------------------------------------------
	プラグイン有効語の設定リンク表示 
----------------------------------------------------------- */
function mcl_slidein_nav_action_links( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', 
		admin_url( 'options-general.php?page=mcl-slidein-nav.php' ), 
		__( 'Settings' , 'mcl-slidein-nav' ) );
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), 'mcl_slidein_nav_action_links', 10, 2 );


/* -----------------------------------------------------------
	テキストドメイン読み込み 
----------------------------------------------------------- */
function mcl_slidein_nav_textdomain() {
	load_plugin_textdomain( 'mcl-slidein-nav', false, dirname( plugin_basename( __FILE__ ) ). '/languages' ); 
}
add_action( 'plugins_loaded', 'mcl_slidein_nav_textdomain' );

/* -----------------------------------------------------------
	管理画面メニューへメニュー項目を追加
----------------------------------------------------------- */
function mcl_slidein_nav_add_admin_menu() {
	add_options_page(
		__( 'Mcl slidein Nav Setting', 'mcl-slidein-nav' ),
		__( 'Mcl Slidein Nav', 'mcl-slidein-nav' ),
		'manage_options',
		'mcl-slidein-nav.php',
		'mcl_slidein_nav_admin' // 定義した関数を呼び出し
	);
}
add_action( 'admin_menu', 'mcl_slidein_nav_add_admin_menu' );

/* -----------------------------------------------------------
	管理画面 CSS ファイル読み込み 
----------------------------------------------------------- */
function mcl_slidein_nav_admin_css($hook) {
    if ( 'settings_page_mcl-slidein-nav' != $hook ) {
        return;
    }
    wp_enqueue_style( 'mcl-admin-style', plugin_dir_url( __FILE__ ). 'css/mcl-admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'mcl_slidein_nav_admin_css' );

/* -----------------------------------------------------------
	アンインストール時のオプションデータ削除 
----------------------------------------------------------- */
function mcl_slidein_nav_uninstall() {
	delete_option( 'mcl_slidein_nav_options' );
}

/* -----------------------------------------------------------
	初期設定
----------------------------------------------------------- */
function mcl_slidein_nav_option_init() {
	// Settings API　オプション設定
	register_setting( 
		'mcl_slidein_nav_group', 
		'mcl_slidein_nav_options'
	);
	
	// アンインストール時の処理
	register_uninstall_hook( __FILE__, 'mcl_slidein_nav_uninstall' );
}
add_action( 'admin_init', 'mcl_slidein_nav_option_init' );

/* -----------------------------------------------------------
	デフォルトオプション値を設定
----------------------------------------------------------- */
function mcl_slidein_nav_default_options() {
	return array(
		'name'		 => '',
		'show_width' => '',
		'push_body'  => 0,
		'position'   => 'left',
		'nav_color'  => 'white',
		'position_top' => 40,
		'position_side' => 40,
	);
}

function get_mcl_slidein_nav_options() {
	return get_option( 'mcl_slidein_nav_options', mcl_slidein_nav_default_options() );
}

/* -----------------------------------------------------------
	管理画面を作成する関数を定義
----------------------------------------------------------- */
function mcl_slidein_nav_admin(){ 
	
	$options = get_mcl_slidein_nav_options();
		
	if ( !current_user_can( 'manage_options' ) )  {    
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );    
    } 
?>	
	<div class="wrap">
	<h2><?php _e( 'Mcl Slidein Nav Setting', 'mcl-slidein-nav' ); ?></h2>
	
	<div class="postbox">
		<form method="post" action="options.php">
		<?php 			
	    settings_fields( 'mcl_slidein_nav_group' );
	    do_settings_sections( 'mcl_slidein_nav_group' );
	    ?>	   
	     
	    <table class="form-table">
        <tbody>

	    <?php // select menu ------------------------- ?>
          <tr>
            <th scope="row">
              <label for="name"><?php _e( 'Choose menu', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	            <select id="name" name="mcl_slidein_nav_options[name]" > 
             	<?php 
	            $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );				
				if( !empty( $menus ) ):
					foreach ( $menus as $menu ) : 					
					$option_name = $menu -> name;
				?>	            
					<option value="<?php echo esc_attr( $option_name ); ?>" 
					<?php if( $option_name == $options['name'] ){ echo 'selected'; } ?>>
					<?php echo esc_attr( $option_name ); ?>
					</option>

				<?php 
					endforeach;
				endif; ?>
				</select><br>
				<p><?php _e( 'Choose what you want to display menus.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>

		<?php // display window width ------------------------- ?>                    
          <tr>
            <th scope="row">
            	<label for="show_width"><?php _e( 'Button\'s display window width', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	        	<input type="text" id="show_width" name="mcl_slidein_nav_options[show_width]" value="<?php if( !empty($options['show_width']) || $options['show_width'] == 0 ){ echo esc_attr( $options['show_width'] ); } ?>"/> px<br>
	        	<p><?php _e( 'Nav button is response displayed in window width. If you always want to display, Nothing input.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>
        
        <?php
	        // var_dump($options);
	        ?>
        <?php // push_body ------------------------- ?>
<!--
		  <tr>
            <th scope="row">
            	<label for="push_body"><?php _e( 'Body push', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	            <input type="checkbox" id="body_push" name="mcl_slidein_nav_options[push_body]" value="1" 
	            <?php if( !empty($options['push_body']) ){ echo 'checked'; } ?>>
	            <br>
	        	<p><?php _e( 'Body push slide in menu.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>
-->
        
          
          <?php // Nav slide in position ------------------------- ?>
		  <tr>
            <th scope="row">
            	<label for="position"><?php _e( 'Nav position', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	        	<select id="position" name="mcl_slidein_nav_options[position]" > 
	            <?php 
	            $positions = array( 'left', 'right');				
				if( isset( $positions ) ):
					foreach ( $positions as $position ) : 					
				?>	
	            	<option value="<?php echo esc_attr( $position ); ?>"
	            	<?php if( $position == $options['position'] ){ echo 'selected'; } ?>>
	            	<?php echo esc_attr( $position ); ?>
	            	</option>
	            
	            <?php 
					endforeach;
				endif; ?>	
	        	</select><br>
	        	<p><?php _e( 'Select the position to display the menu.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>
          
          <?php // Nav color ------------------------- ?>
           <tr>
            <th scope="row">
            	<label for="position"><?php _e( 'Nav theme color', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	        	<select id="position" name="mcl_slidein_nav_options[nav_color]" > 
	            <?php 
	            $nav_colors = array( 'white', 'dark');				
				if( isset( $nav_colors ) ):
					foreach ( $nav_colors as $nav_color ) : 					
				?>	
	            	<option value="<?php echo esc_attr( $nav_color ); ?>"
	            	<?php if( $nav_color == $options['nav_color'] ){ echo 'selected'; } ?>>
	            	<?php echo esc_attr( $nav_color ); ?>
	            	</option>
	            
	            <?php 
					endforeach;
				endif; ?>	
	        	</select><br>
	        	<p><?php _e( 'Select the menu theme color, white or dark.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>
          
		  <?php // Nav botton position top ------------------------- ?>
	        <tr>
            <th scope="row">
            	<label for="position_top"><?php _e( 'Nav button position top', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	        	<input type="text" id="position_top" name="mcl_slidein_nav_options[position_top]" value="<?php if( !empty($options['position_top']) ){ echo esc_attr( $options['position_top'] ); } ?>"/> px<br>
	        	<p><?php _e( 'Input of Nav button\'s position from window top.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>

			<?php // Nav botton position top ------------------------- ?>          
          <tr>
            <th scope="row">
            	<label for="position_side"><?php _e( 'Nav button position side', 'mcl-slidein-nav' ); ?></label>
            </th>
            <td>
	        	<input type="text" id="position_side" name="mcl_slidein_nav_options[position_side]" value="<?php if( !empty($options['position_side']) ){ echo esc_attr( $options['position_side'] ); } ?>"/> px<br>
	        	<p><?php _e( 'Input of Nav button\'s position from window side.', 'mcl-slidein-nav' ); ?></p>
	        </td>
          </tr>
                    
        </tbody>
      </table>
      <?php submit_button(); ?>
	    	    
		</form>
	</div>
	</div>
<?php
	
	 $options_ =  get_theme_mod('position_top_value');
/*
	 $options_2 =get_option( "theme_mods_twentysixteen" );
var_dump( $options_2 );
*/

	
} 

/* -----------------------------------------------------------
	load CSS & JS 
----------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mcl_slidein_nav_scripts' );
function mcl_slidein_nav_scripts(){
	$options = get_mcl_slidein_nav_options();
	
	
	if( $options['nav_color'] === 'white' ){
		wp_enqueue_style( 'mcl_nav_white' , plugins_url('css/white-style.css', __FILE__) );
	} else {
		wp_enqueue_style( 'mcl_nav_dark' , plugins_url('css/dark-style.css', __FILE__) );
	}
	
	wp_enqueue_script('mcl_slidein_nav_js', plugins_url( '/js/function.js', __FILE__ ), array( 'jquery' ));	
	$mcl_nav_options = array(
		//'push_body' => $options['push_body'],
		'position' => $options['position'],
		'position_top' => $options['position_top'],
		'position_side' => $options['position_side'],
	);
	
	wp_localize_script( 'mcl_slidein_nav_js', 'mcl_slidein_nav', $mcl_nav_options );
} 

/* -----------------------------------------------------------
	output HTML header
----------------------------------------------------------- */
// ヘッダーへスタイル書き出し
add_action('wp_head', 'mcl_slidein_nav_style');
function mcl_slidein_nav_style(){ 

	$options = get_mcl_slidein_nav_options();
	if( !empty( $options['name'] ) ): ?>
		<style type="text/css" >
			.mcl_nav_btn{
				<?php 
					echo esc_html( $options['position'] ).':'.esc_html( $options['position_side'] ).'px;';
					echo 'top:'.esc_html( $options['position_top'] ).'px;'.PHP_EOL;
				?>
			}
			
			<?php if( isset( $options["show_width"] ) ): ?>
			@media only screen and (min-width: <?php echo esc_html( $options["show_width"] ); ?>px) {
				.mcl_nav_btn{
					display: none;
				}
			}	
			<?php endif; ?>
		</style>
	<?php 
	endif; 
} 

/* -----------------------------------------------------------
	output HTML footer
----------------------------------------------------------- */
add_action('wp_footer', 'mcl_slidein_nav_func', 100);
function mcl_slidein_nav_func(){
	
	$options = get_mcl_slidein_nav_options();
	if( !empty( $options['name'] ) ){ ?> 
				
		<div id="mcl_slidein_nav" class="mcl_nav_wrap <?php echo $options['position'] ?>">
		<button id="mcl_slidein_nav_btn" class="mcl_nav_btn">
			<span class="btn_border"></span>
			<span class="btn_border middle"></span>
			<span class="btn_border"></span>
		</button>
		<ul id="mcl_slidein_nav_list" class="mcl_nav_list">
			<?php wp_nav_menu( 
				array(
					'menu'       => $options['name'],
					'container'  => false,
					'items_wrap' => '%3$s'
				) 
			); ?>
		</ul>
		</div>
		<div id="mcl_slidein_nav_layer" class="mcl_nav_layer"></div>
	<?php 
	} 
}  
//$options['position_top']
define('POSITION_TOP', 'position_top'); //セクションIDの定数化
define('POSITION_TOP_VALUE', 'position_top_value'); //セッティングIDの定数化

function themename_theme_customizer( $wp_customize ) {
 $wp_customize->add_section( POSITION_TOP , array(
 'title' => 'メニューの位置', //セクション名
 'priority' => 100, //カスタマイザー項目の表示順
 'description' => 'メニューのトップからの位置', //セクションの説明
 ) );
 
$wp_customize->add_setting( POSITION_TOP_VALUE ); 
$wp_customize->add_control( POSITION_TOP_VALUE, array(
    'settings' => POSITION_TOP_VALUE,
    'label' =>'テストテキストフィールド',
    'section' => POSITION_TOP,
    'type' => 'text',
));
/*
 $wp_customize->add_setting( POSITION_TOP_VALUE );
 $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, POSITION_TOP_VALUE, array(
 'label' => 'ロゴ', //設定ラベル
 'section' => POSITION_TOP, //セクションID
 'settings' => POSITION_TOP_VALUE, //セッティングID
 'description' => '画像をアップロードするとヘッダーにあるデフォルトのサイト名と入れ替わります。',
 ) ) );
*/
}
add_action( 'customize_register', 'themename_theme_customizer' );//カスタマイザーに登録
 
//ロゴイメージURLの取得
/*
function get_the_logo_image_url(){
 return esc_url( get_theme_mod( POSITION_TOP_VALUE ) );
}
*/
