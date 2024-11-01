<?php
    class scb_utilities {


        public static function sanitizePostInput($post)
        {   

            foreach ($post as $k => $command) {
                foreach ($command as $i => $field) {
                    $post[$k][$i] = sanitize_text_field($field);
                }
            }
            return $post;
        }


        public static function scb_getCategory() {
            $array = array();
            foreach (get_categories() as $cat) {
                $array[$cat->term_id] = $cat->name;
            }
            return $array;
        }
    
        public static function scb_getTag() {
            $array = array();
            foreach (get_terms('post_tag') as $cat) {
                $array[$cat->term_id] = $cat->name;
            }
            return $array;
        }


    }



?>