<?php

namespace CustomSlider\ElementorWidgets;

use Elementor\Widget_Base;
// use Elementor\Control_Manager;



class Custom_Slider extends Widget_Base {         // tworzymy klasę, która jest rozszerzana przez Widget_Base

    public function __construct($data = array(), $args = null){
        parent::__construct($data, $args);
//        wp_enqueue_style('style-handle', plugin_dir_url( __FILE__ ) . "../assets/css/style.css" );  // to nas zabiera do naszego pliku
    }
    
    public function get_name() {
        return 'RBIT slider';
    }

    public function get_title() {
        return esc_html__( 'RunByIT slider', 'Custom' ); // widoczne dla użytkownika
    }

    public function get_icon() {
        // return 'fa fa-menu';
        return 'eicon-apps';
    }

    public function get_categories() {
        return ['RBIT'];
    }

    
	public function get_style_depends() {
		return [ 'style-handle' ];
	}
  
    public function get_keywords() {  // kiedy nam szuka 
        return [ 'slider', 'custom' ];
    }






    protected function register_controls() {


		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


        // START REPEATER ------------


        $repeater = new \Elementor\Repeater();

//		$repeater->add_control(
//			'title',
//			[
//				'label' => esc_html__( 'Title', 'elementor_widget' ),
//				'type' => \Elementor\Controls_Manager::TEXT,
//				'default' => esc_html__( 'Title', 'elementor_widget' ),
//			]
//		);


		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);


        // END REPEATER ------------


         $this->add_control('Slider', array(
             'label' => esc_html__( 'slider', 'elementor_widget'),
             'type' => \Elementor\Controls_Manager::REPEATER,
             'fields' => $repeater->get_controls(),
         ));
        
		
		$this->end_controls_section();

	}





        
    protected function render() {
	

    
    $settings = $this->get_settings_for_display();
		?>
<!--		<div>-->
<!--            <div class="row text-center justify-content-between mb-5 ">-->
<!---->
<!---->
<!--            --><?php //foreach ( $settings['Slider'] as  $item ) : ?>
<!--            <img src="--><?//= str_replace( '.jpg','-300x319.jpg', $item['image']['url'] )  ?><!--" />-->
<!--            --><?php //endforeach; ?>
<!---->
<!---->
<!---->
<!--		    </div>-->
<!--        </div>-->




        <div class="swiper rbit-swiper noLightbox">
            <div class="swiper-wrapper noLightbox">

        <?php foreach ( $settings['Slider'] as  $item ) :
            $image_src_big = $item['image']['url'];
            $image_src = str_replace( '.jpg','-300x319.jpg', $item['image']['url'] ) ?>



        <div class="swiper-slide noLightbox">

                        <a  href="<?php echo $image_src_big; ?>" data-elementor-lightbox-slideshow="rbit_slider" >
                            <img class="slider-image noLightbox"  src="<? echo $image_src; ?>"/>
                        </a>
                    </div>

                    <?php endforeach;


                ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Initialize Swiper -->
        <script>
            // var elementorFrontendConfig = {"kit":{"global_image_lightbox":"no"}};

            createNewSwiper = () => {
                var swiper = new Swiper(".rbit-swiper", {
                    slidesPerView: 4,
                    spaceBetween: 5,
                    slidesPerGroup: 1,
                    loop: false,
                    loopFillGroupWithBlank: true,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    breakpoints: {
                        // when window width is >= 320px
                        100: {
                            slidesPerView: 1,
                        },
                        480: {
                            slidesPerView: 2,
                        },
                        769: {
                            slidesPerView: 4,
                        },
                        1000: {
                            slidesPerView: 5,
                        },


                    },
                });
            }


            // checkWidth();
            createNewSwiper();




        </script>


        <?php
	}




	protected function content_template() {

	}

}