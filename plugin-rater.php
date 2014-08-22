<?php
/*
Plugin Name: Plugin Rater
Plugin URI: http://natasha.jp/
Description: It makes you access to plugins pages of wordpress.org easier.
Author: natashanatashanatashanatasha
Contributors: natashanatashanatashanatasha, jim912
Version: 0.1
Author URI: http://natasha.jp/
License: GPL2+
Text Domain: plugin-rater
Domain Path: /languages/
*/

define( 'PLUGIN_MEMORANDUM_VER', '0.1' );
/*
function load_plugin_memorandum_textdomain() {
	// プラグインの翻訳ファイル（日本語ならplugin-memorandum-ja.mo）の読み込み
	load_plugin_textdomain( 'plugin-memorandum', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
// プラグインページのロード（初期設定読み込み完了時）にload_plugin_memorandum_textdomainが実行されるようフックを追加
add_action( 'load-plugins.php', 'load_plugin_memorandum_textdomain' );
*/

// natasha add
function load_plugin_rater() {
	// 該当プラグインのWordPress.org プラグインページへのリンクを貼る
	// 該当プラグインの現在のレートを表示する
}
// プラグインページのロード（初期設定読み込み完了時）にload_plugin_raterが実行されるようフックを追加
add_action( 'load-plugins.php', 'load_plugin_rater' );

/*
function add_memorandum_deactivation_hook() {
	// プラグインの停止時にdelete_memorandum_optionが実行されるようにフック処理を追加
	register_deactivation_hook( __FILE__ , 'delete_memorandum_option' );
}
// 管理画面の起動完了時にadd_memorandum_deactivation_hookが実行されるようフックを追加
add_action( 'admin_init', 'add_memorandum_deactivation_hook' );

function delete_memorandum_option( $network_wide ) {
	// ネットワークで停止されたか否かの判別
	if ( $network_wide ) {
		global $wpdb;
		// ネットワーク管理者向けのメモを削除
		delete_site_option( 'plugin_memorandum' );
		// 作成された子サイトのidを全て取得
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' ORDER BY blog_id ASC" );
		// foreachでループ
		foreach ( $blog_ids as $blog_id ) {
			// 各サイトのメモを削除
			delete_blog_option( $blog_id, 'plugin_memorandum' );
		}
	} else {
		// マルチサイトかつネットワーク管理者かどうかを判別
		if ( is_multisite() && is_super_admin() ) {
			// ネットワーク管理者のメモを削除
//			他のサイトで有効化されていないかの検証が必要なので現状実行しない
//			delete_site_option( 'plugin_memorandum' );
		} else {
			// 子サイトのメモを削除
			delete_option( 'plugin_memorandum' );
		}
	}
}
*/


function rater_row( $plugin_file, $plugin_data ) {
	global $wp_list_table;

	// テーブルの表示カラム数を取得
	list( $columns, $hidden ) = $wp_list_table->get_column_info();
	// colspanに表示するカラム数を計算
	$colspan = count( $columns );

	// ネットワーク管理者かどうかを判別
	if ( is_super_admin() ) {
		/*
		// サイトオプションからメモ内容を取得
		$plugin_memorandum = get_site_option( 'plugin_memorandum' );
		*/
		//natasha add
		//該当プラグインの名前を取得
		$plugin_name = sanitize_title( $plugin_data['TextDomain'] );
		//該当プラグインのWordPress.orgのURLを取得
		$plugin_orgurl = 'https://wordpress.org/plugins/'.$plugin_name.'/';
		//該当プラグインのレートを取得
		
	} else {
		// 何もしない
	}
	/*
	// 表示するプラグインのメモ内容を抜き出し。メモがなければ空
	$memo = isset( $plugin_memorandum[$plugin_file] ) ? $plugin_memorandum[$plugin_file] : '';
	// cookieの保存名を取得
	$cookie_id = urldecode( sanitize_title( $plugin_data['Name'] ) );
	// cookieにメモが開いている状態の設定がなければ、hiddenのclassを出力するように
	$hidden_class = isset( $_COOKIE['pluginMemo'][$cookie_id] ) ? '' : ' hidden';
	// メモ行の表示
	*/
		
?>
<tr class="plugin_infoupdater">
	<td colspan="<?php echo esc_attr( $colspan ); ?>">
		<a href="<?php echo $plugin_orgurl; ?>" target="_blank">Let's rate this plugin!!</a>
	</td>
</tr>
<?php
}
// プラグイン毎の行表示終了時にmemorandum_rowが実行されるようフックを追加
add_action( 'after_plugin_row', 'rater_row', 10, 2 );

//以下よくわからないので省略