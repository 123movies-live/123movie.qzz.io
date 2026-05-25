<?php


namespace App\Traits;


trait Menu{

    public function CreateAdminMenu(){
        add_menu_page(
            'Movies',
            'Movies',
            'administrator',
            'moviewp_movie',
            array($this,'load_page'),
            'dashicons-editor-video',
            null
        );
        add_menu_page(
            'Discover',
            'Discover',
            'administrator',
            'moviewp_discover',
            array($this,'load_page'),
            'dashicons-code-standards',
            null
        );
        add_menu_page(
            'TV',
            'TV',
            'administrator',
            'moviewp_tv',
            array($this,'load_page'),
            'dashicons-desktop',
            null
        );
        add_menu_page(
            'TV Discover',
            'TV Discover',
            'administrator',
            'moviewp_tv_discover',
            array($this,'load_page'),
            'dashicons-desktop',
            null
        );
        add_submenu_page(
            'moviewp_post',
            'The Movie DB Importer',
            'The Movie DB',
            'administrator',
            'moviewp_movie',
            array($this,'load_page')
        );
        add_submenu_page(
            'moviewp_post',
            'Movie Boot',
            'Movie Boot',
            'administrator',
            'moviewp_movie',
            array($this,'load_page')
        );
    }

    public function CustomAdminItemMenu(){
        global $submenu;
        @$submenu['moviewp_post'][0][0] = 'Import';
    }

}