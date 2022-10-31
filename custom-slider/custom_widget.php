<?php
/**
 * Plugin Name: Custom widget for elementor
 * Author: Michal
 * Description: Elementor Widget
 * Version: 0.1.0
 * text-domain: cos-robi
*/

 
namespace CustomSlider;
use CustomSlider\ElementorWidgets\Custom_Slider;         // nazwa drugiego pliku, ostatnie to klasa dodająca nam widget


if ( ! defined( 'ABSPATH' ) ) {             // jeśli ktoś będzie nam chciał schakować to wywali
    exit;   
}


final class CustomWidget {       // final, ponieważ nie ma żadnej klasy nad nią

    // const VERSION = '0.1.0';
    // const ELEMENTOR_MINIMUM_VERSION = '3.0.0';
    // const PHP_MINIMUM_VERSION = '7.0';

    //  <-------------- Singleton ------------>         // Tylko raz może się odpalić 

    private static $_instance = null;
    public static function get_instance() {         // s

        if ( null == self::$_instance ) {
            self::$_instance = new Self();
        }
        return self::$_instance;
    }



    const VERSION = '1.0.0';   // wersja naszego widgetu
    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';      // minimalna wersja elementora
    const MINIMUM_PHP_VERSION = '10.0';             // minimalna wersja php

    public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}
		return true;
	}


    public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

    public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-addon' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

    public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-addon' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
    

// <------------ TWORZENIE NOWEJ KATEGORI -------------->

    function create_new_category( $elements_manager ) {
        $elements_manager->add_category(
            'RBIT',
            [
                'title' => esc_html__( 'RunByIT slider', 'Custom widget for elementor' ),    // nazwa, plugin name
                'icon' => 'fa fa-plug'          // ikonka
            ]
        );
    }

    public function i18n() {            // coś do tłumaczenia na inne języki 
        load_plugin_textdomain( 'RBIT' );
    }

    public function __construct() {
        add_action( 'init', array($this, 'i18n'));      // dodajemy text-domain, wywołujemy funkcje i18n na naszym obiekcie $this
        add_action( 'plugins_loaded', array($this, 'init_plugin' )); // kiedy plugint są załadowane to dodajemy nasz plugin
        add_action( 'elementor/widgets/widgets_registered', array($this, 'init_widgets' )); // dodawanie i inicjalizowanie widgetu
        add_action( 'elementor/elements/categories_registered', array($this, 'create_new_category' ));  // dodanie kategorii   
    }

    public function init_plugin() {         // jak damy plugin to inicjaloizujemy w nim widgety, dodajemy plugin ale dopiero wtedy kiedy w __construct (odpala się pierwszy), to wtedy inicjalizujemy
        // $this->init_widgets();
    }

    public function init_controls() {      // tutaj dodajemy nasz controls 
    }

    public function init_widgets() {        // tutaj dodajemy nasz widget
        require_once __DIR__ . '/widgets/custom-slider.php';      // wymagany jest plik z widgetem
        \Elementor\Plugin::instance()->widgets_manager->register( new Custom_Slider() );  // Musimy go zarejestrować w elementorze
    }
}

CustomWidget::get_instance();        // wywołanie naszego Singleton