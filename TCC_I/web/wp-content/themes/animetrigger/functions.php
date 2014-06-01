<?php
/**************************************
 *  THEME SUPORT
 **************************************/
function add_suport_theme(){
    add_theme_support('menus');
    add_theme_support( 'post-thumbnails' );
}
add_action('after_setup_theme','add_suport_theme');
/**************************************
 *  END! THEME SUPORT
 **************************************/



/**************************************
 *      REGISTRO DE MENUS
 **************************************/
register_nav_menus( 
                array(
                    'main-menu'=> __('Main Menu'),
                    'redes-sociais'=> __('Redes Sociais') 
                )
        );
/**************************************
 *      END! REGISTRO DE MENUS
 **************************************/



/**************************************
 *      TAMANHOS DE IMAGENS
 **************************************/
add_image_size( '260x341', 260 , 341 , true );



/**************************************
 * CARREGAR JS
 **************************************/
function load_my_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap',get_bloginfo('stylesheet_directory','raw').'/js/bootstrap.js',array('jquery'));
    wp_enqueue_script('functions',get_bloginfo('stylesheet_directory','raw').'/js/functions.js',array('jquery','bootstrap'));
}
add_action( 'wp_enqueue_scripts', 'load_my_scripts');



//JS no Admin
function load_my_scripts_admin() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('functions',get_bloginfo('stylesheet_directory','raw').'/admin/js/functions.js',array('jquery'));
}
add_filter('admin_head', 'load_my_scripts_admin');
/**************************************
 * END! CARREGAR JS
 **************************************/


/**************************************
 * CARREGAR CSS
 **************************************/
function load_my_CSS() {
    wp_enqueue_style( 
            'bootstrap',
            get_template_directory_uri().'/css/bootstrap.css'
            );
}
add_action( 'wp_enqueue_scripts', 'load_my_CSS' );


//JS no Admin
function load_my_CSS_admin() {
}
add_filter('admin_head', 'load_my_CSS_admin');
/**************************************
 * END! CARREGAR JS
 **************************************/





/**************************************
 *      REGISTRO DE POST TYPE
 **************************************/
function add_post_types(){
    /***********************
     * Autores
     ***********************/
    $labels = array(
        'singular_name' =>  __('Autor'),
        'name' => __('Autores'),
        'add_new_item' => __('Adicionar novo Autor'),
        'edit_item' => __('Editar Autor'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Esse post type, Ž para cadastrarmos todos autores'),
        'public' => true,
        'capability_type' => 'post',
        'supports' => array('title','editor','thumbnail'),
    ); 
    register_post_type('autores',$args);
    /************************
     * END! Autores
     ***********************/
    
    /***********************
     * Series / Franquia
     ***********************/
    $labels = array(
        'singular_name' =>  __('Serie'),
        'name' => __('Series'),
        'add_new_item' => __('Adicionar nova Serie'),
        'edit_item' => __('Editar Serie'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Esse post type, Ž para cadastrarmos todas as series'),
        'public' => true,
        'capability_type' => 'post',
        'supports' => array('title','editor','thumbnail'),
        'show_in_nav_menus' => true,
    ); 
    register_post_type('series',$args);
    /************************
     * END! Series / Franquia
     ***********************/
    
    /***********************
     * Material (Animes, Mangas...)
     ***********************/
    $labels = array(
        'singular_name' =>  __('Material'),
        'name' => __('Materiais'),
        'add_new_item' => __('Adicionar novo Material'),
        'edit_item' => __('Editar Material'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Esse post type, Ž para cadastrarmos os novos materiais das series'),
        'public' => true,
        'capability_type' => 'post',
        'supports' => array('title','editor'),
    ); 
    register_post_type('materiais',$args);
    /************************
     * END! Material
     ***********************/

    
    /***********************
     * Capitulos
     ***********************/
    $labels = array(
        'singular_name' =>  __('Ocorrencia'),
        'name' => __('Ocorrencias'),
        'add_new_item' => __('Adicionar nova Ocorrencia'),
        'edit_item' => __('Editar Ocorrncia'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Esse post type, Ž para cadastrarmos as ocorrncias(capitulos,episodios,clips,videos,etc) de um material'),
        'public' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'supports' => array('title'),
    ); 
    register_post_type('ocorrencias',$args);
    /************************
     * END! Capitulos
     ***********************/
    
    
    /************************
     * Servidores
     ***********************/
    $labels = array(
        'singular_name' =>  __('Servidor'),
        'name' => __('Servidores'),
        'add_new_item' => __('Adicionar novo Servidor'),
        'edit_item' => __('Editar Servidor'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Esse post type, Ž para cadastrarmos as servidores onde estar‹o hospedados uma determinada ocorrncia'),
        'public' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'supports' => array('title'),
    );
    register_post_type('servidores',$args);
    /************************
     * END! Servidores
     ***********************/
    
}
add_action( 'init', 'add_post_types' );
/**************************************
 *      END! REGISTRO DE POST TYPE
 **************************************/


/**************************************
 *      COLUNAS ADICIONAIS
 **************************************/
    function post_column_views($colunas){
        switch (get_post_type()){
            case 'materiais':
                $tmp = array_pop($colunas);
                $colunas['multimidia'] = __("Multimidia");
                $colunas['date'] = __('Date');
            break;
            case 'ocorrencias':
                $tmp = array_pop($colunas);
                $colunas['multimidia_ocorrencia'] = __("Multimidia");
                var_dump($colunas);
                $colunas['date'] = __('Date');
            break;
        }
        return $colunas;
    }
 
    //Function that Populates the 'Views' Column with the number of views count.
    function post_custom_column_views($coluna, $post_id){
        global $post;
        switch ($coluna){
            case 'multimidia':
                $multimidias = $terms = get_the_terms($post_id,'multimidias'); 
                foreach($multimidias as $multimidia){
                    $echo[] = $multimidia->name;
                }
                echo implode(', ', $echo);
            break;
            case 'multimidia_ocorrencia':
                $connected = p2p_type( 'material_ocorrencia' )->get_connected( $post );
                    while ( $connected->have_posts() ){
                        $connected->the_post();
                        $multimidias = get_the_terms($post->ID,'multimidias');
                        foreach($multimidias as $multimidia){
                            $echo[] = $multimidia->name;
                        }
                }
                echo implode(', ', $echo);
            break;
        }
    }
    add_filter('manage_posts_columns', 'post_column_views');
    add_action('manage_posts_custom_column', 'post_custom_column_views',10,2);
/**************************************
 *      END! COLUNAS ADICIONAIS
 **************************************/



/**************************************
 *      REGISTRO DE TAXONOMY
 **************************************/
function add_taxonomy(){
    /************************
     * Fansubs
     ***********************/
    $labels = array(
                    'name' => __( 'Fansubs' ),
                    'singular_name' => __( 'Fansub'),
                    'all_items' => __('Todas Fansubs'),
                    'edit_item' => __('Editar Fansub'),
                    'add_new_item' => __('Adicionar nova Fansub'),
                    'separate_items_with_commas' => __('Separe as Fansubs com virgulas'),
                    'add_or_remove_items'=> __('Adicionar ou remover Fansubs'),
                    'menu_name' => __('Fansub'),
                    'choose_from_most_used' => __('Escolha as fansubs mais usadas'),
                    'not_found' => __('Fansub n‹o encontrada'),
                ); 

    register_taxonomy(
                'fansub',
                array('series','materiais'),
                array(
                    'labels' => $labels,
                    )
            );
    /************************
     * END! Fansubs
     ***********************/
    
    /************************
     * Multimidias
     ***********************/
    $labels = array(
                    'name' => __( 'Multimidias' ),
                    'singular_name' => __( 'Multimidia'),
                ); 

    register_taxonomy(
                'multimidias',
                array('materiais'),
                array(
                    'labels' => $labels,
                    )
            );
    /************************
     * END! Multimidias
     ***********************/
    
    /************************
     * Generos
     ***********************/
    $labels = array(
                    'name' => __( 'Generos' ),
                    'singular_name' => __( 'Genero'),
                ); 

    register_taxonomy(
                'generos',
                array('series','materiais'),
                array(
                    'labels' => $labels,
                    )
            );
    /************************
     * END! Generos
     ***********************/
    
    /************************
     * Temas
     ***********************/
    $labels = array(
                    'name' => __( 'Temas' ),
                    'singular_name' => __( 'Tema'),
                ); 

    register_taxonomy(
                'temas',
                array('series','materiais'),
                array(
                    'labels' => $labels,
                    'hierarchical' => true
                    )
            );
    /************************
     * END! Temas
     ***********************/
    
    /************************
     * Formatos
     ***********************/
    $labels = array(
                    'name' => __( 'Formatos' ),
                    'singular_name' => __( 'Formato'),
                ); 

    register_taxonomy(
                'formatos',
                array('ocorrencias'),
                array(
                    'labels' => $labels,
                    )
            );
    /************************
     * END! Formatos
     ***********************/
    

}
add_action( 'init', 'add_taxonomy' );
/**************************************
 *      END! REGISTRO DE TAXONOMY
 **************************************/




/**************************************
 *      REGISTRO DE LIGA‹ES POSTS 2 POSTS
 **************************************/
function my_connection_types(){
    /************************
     * Autor/Serie 
     ************************/
    p2p_register_connection_type(
                                array(
                                        'name' => 'autor_serie',
                                        'from' => 'autores',
                                        'to' => 'series',
                                        'title' => __('Autoria'),
                                    )
                                );
    /************************
     * END! Autor/Serie 
     ************************/

    /************************
     * Serie/Anime 
     ************************/
    p2p_register_connection_type(
                                array(
                                        'name' => 'serie_material',
                                        'from' => 'series',
                                        'to' => 'materiais',
                                        'sortable' => 'from',
                                        'title' => __('Serie'),
                                        'cardinality' => 'one-to-many',                                    
                                        'admin_column' => 'to',
                                        'to_labels' => array(
                                                            'column_title' => 'Serie',
                                                          ),               
                                    )
                                );
    /************************
     * END! Serie/Anime 
     ************************/
    
    
    
    /************************
     * Anime/Epis—dio 
     ************************/
    p2p_register_connection_type(
                                array(
                                        'name' => 'material_ocorrencia',
                                        'from' => 'materiais',
                                        'to' => 'ocorrencias',
                                        'cardinality' => 'one-to-many',
                                        'sortable' => 'numero',
                                        'fields' => array(
                                                    'numero' => array(
                                                            'title' => 'Nœmero',
                                                            'type' => 'text',
                                                            ),
                                                    ),
                                        'admin_column' => 'to',
                                        'to_labels' => array(
                                                            'column_title' => 'Meterial',
                                                          ),               

                                    )
                                );
    /************************
     * END! Anime/Epis—dio 
     ************************/
    
    
    
    /************************
     * Epis—dio/Servidor 
     ************************/
    p2p_register_connection_type(
                                array(
                                        'name' => 'episodio_servidor',
                                        'title' => __('Links'),
                                        'from' => 'ocorrencias',
                                        'to' => 'servidores',
                                        'cardinality' => 'many-to-many',
                                        'admin_box' => 'from',
                                        'duplicate_connections' => true,
                                        'fields' => array(
                                                    'formato' => array(
                                                            'title' => 'Formato',
                                                            'type' => 'select',
                                                            'values' => get_formatos_suportados_ocorrencia(),
                                                            ),
                                                    'link' => array(
                                                            'title' => 'Link',
                                                            'type' => 'text',
                                                            ),
                                                    'online' => array(
                                                            'title' => 'On',
                                                            'type' => 'checkbox',
                                                            ),
                                                    ),
                                    )
                                );
    /************************
     * END! Epis—dio/Servidor
     ************************/       
}
add_action( 'p2p_init', 'my_connection_types' );
/**************************************
    *      END! REGISTRO DE LIGA‚ÍES
 **************************************/




/**************************************
 *      FUN‚ÍES AUXILIARES
 **************************************/
function labels($singular,$plural,$tipo){
    switch($tipo){
        case 'taxonomy':
            return __label_taxonomy($singular,$plural);
            break;  
        case 'post_type':
            return __label_post_type($singular,$plural);
            break;
    }
}

function __label_taxonomy($singular,$plural){
    return array(
		'name'                       => __( $plural),
		'singular_name'              => __( $singular),
		'search_items'               => __( 'Buscar '.$plural ),
		'popular_items'              => __( $plural.' Popular' ),
		'all_items'                  => __( 'Todos(as) '.$plural ),
		'edit_item'                  => __( 'Editar '.$singular ),
		'update_item'                => __( 'Update '.$singular ),
		'add_new_item'               => __( 'Adicionar Novo(a) '.$singular ),
		'new_item_name'              => __( 'Novo(a) '.$singular ),
		'separate_items_with_commas' => __( 'Separa os(as) '.$plural.' com virgulas' ),
		'add_or_remove_items'        => __( 'Adicionar ou remover '.$plural ),
		'choose_from_most_used'      => __( 'Escolha dentre os(as) '.$plural.' mais usados(as)' ),
		'not_found'                  => __( 'Nenhum(a) '.$singular.' encontrado(a)' ),
		'menu_name'                  => __($plural),
	);
}
function __label_post_type($singular,$plural){
    return array(
            'name' => __($plural),
            'singular_name' => __($singular),
            'add_new' => __('Adicionar novo(a) '),
            'add_new_item' => __('Adicionar novo(a) '.$singular),
            'edit_item' => __('Editar '.$singular),
            'new_item' => __('Novo(a) '.$singular),
            'all_items' => __('Todos(as) '.$plural),
            'view_item' => __('Ver '.$singular),
            'search_items' => __('Buscar '.$plural),
            'not_found' =>  __('Nenhum(a) '.$singular.' encontrado(a)'),
            'not_found_in_trash' => __('Nenhum(a) '.$singular.' achado na lixeira'), 
            'menu_name' => __($plural),
	);
}

function the_excerpt_max_charlength($num_words) {
	$excerpt = get_the_excerpt();
	$explode = explode(" ", $excerpt);
        if (count($explode) > $num_words ) {
                $string = "";
                for($w=0 ; $w < $num_words ; $w++ ){
                    $string .= " ".$explode[$w];
                }
                echo trim($string);
		echo apply_filters('excerpt_more', '');
	} else {
		echo $excerpt;
	}
}



/*
 * Essa Fun‹o Ž para retornar a lista de formatos suportados de uma ocorrencia
 */
function get_formatos_suportados_ocorrencia($ocorrencia_id = NULL){
    if($ocorrencia_id==NULL){
        //Retorna todos os formatos
        $formatos = get_terms('formatos',array('hide_empty'=>FALSE));
        $termos = array();
        foreach ($formatos as $formato) {
            $termos[$formato->slug] = $formato->name; 
        }
        return $termos;
    }
}



/*
 * Leia mais ...
 */
function new_excerpt_more( $excerpt ) {
	return str_replace( '[...]', '...', $excerpt );
}
add_filter( 'wp_trim_excerpt', 'new_excerpt_more' );
/**************************************
 *      END! FUN‹ES AUXILIARES
 **************************************/
?>