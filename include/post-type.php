<?php 
    class scb_post_type {

        private $name           = '';
        private $post_typeVar   = '';
        private $icon           = '';

        private $label      = array();
        private $args      = array();

        public function __construct($name, $label, $icon) {
            $this->setName($name);
            $this->setPostTypeVar($label);
            $this->setIcon($icon);

            $this->createLabel();
            $this->createArgs();

            $this->createPostType();
        }

        public function create() {
            $name   =   $this->getName();
            $label  =   $this->getLabel();
        }

        public function setName ($name) {
            $this->name = $name;
        }

        public function getName () {
            return $this->name;
        }

        public function setIcon ($icon) {
            $this->icon = $icon;
        }

        public function getIcon () {
            return $this->icon;
        }

        public function setPostTypeVar ($post_typeVar) {
            $this->post_typeVar = $post_typeVar;
        }

        public function getPostTypeVar () {
            return $this->post_typeVar;
        }

        public function createLabel () {
            $labels = array(
                'name' => $this->getName(),
                'singular_name' => $this->getName(),
                'add_new' => __( 'Add New', 'social-bot' ),
                'add_new_item' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'Add New %s', 'social-bot' ),
                    $this->getName()
                ),
                'edit_item' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'Edit %s', 'social-bot' ),
                    $this->getName()
                ),
                'new_item' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'New %s', 'social-bot' ),
                    $this->getName()
                ),
                'all_items' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'All %s', 'social-bot' ),
                    $this->getName()
                ),
                'view_item' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'View %s', 'social-bot' ),
                    $this->getName()
                ),
                'search_items' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'Search %s', 'social-bot' ),
                    $this->getName()
                ),
                'not_found' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'No %s found', 'social-bot' ),
                    $this->getName()
                ),
                'not_found_in_trash' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'No %s found  in the Trash', 'social-bot' ),
                    $this->getName()
                ),
                'parent_item_colon' => '',
                'menu_name' => $this->getName()
            );

            $this->label = $labels;

        }

        public function getLabel () {
            return $this->label;
        }

        public function createArgs() {

            $args = array(
                'labels' => $this->getLabel(),
                'description' => sprintf(
                    /* translators: %s: the post type name. */
                    __( 'Displays %s and their ratings', 'social-bot' ),
                    $this->getName()
                ),
                'public' => true,
                'menu_position' => 4,
                'supports' => array( 'title' ),
                'has_archive' => false,
                'menu_icon'           => $this->getIcon(),
            );
            $this->args = $args;
        }

        public function getArgs () {
            return $this->args;
        }

        public function createPostType() {
            register_post_type( $this->getPostTypeVar(), $this->getArgs() );

        }
    }