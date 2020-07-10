<?php

namespace MJsonV {

    class View {

        private $url;
        private $attrs;
        private $idAttr;

        public function __construct ( string $url, array $attributes, string $idAttr ) {
            $this->url = $url;
            $this->attrs = $attributes;
            $this->id = $idAttr;
        }
    
        public function createElement ( string $element, array $props = [], string $child = "" ) {
            $html = '<' . $element;
            if ( ! empty( $props ) ) {
                $pairs = [];
                foreach ( $props as $prop => $value ) {
                    if ( $value !== null ) {
                        $pairs[] = $prop . '="' . htmlspecialchars( $value ) . '"';
                    } else {
                        $pairs[] = $prop;
                    }
                }
                $html .= ' ' . implode( ' ', $pairs );
            }
            $html .= '>';
            $html .= $child;
            $html .= '</' . $element . '>';
            return $html;
        }

        public function getTableHeader () {
            $html = '<thead><tr>';
            foreach ( $this->attrs as $attr ) {
                $html .= '<th>' . $attr . '</th>';
            }
            $html .= '</tr></thead>';
            return $html;
        }
        
        public function getTableBody () {
            $html = '<tbody><tr v-for="item in items">';
            foreach ( $this->attrs as $attr ) {
                $html .= '<td name="' . $attr . '" v-on:click="toSingle"><a>{{ item.' . $attr . ' }}</a></td>';
            }
            $html .= '</tr></tbody>';
            return $html;
        }
        
        public function getLoaderImage () {
            $html = '<figure><img src="#" alt="Carregando dados..." /></figure>';
            return $html;
        }
    
    }

}
