<?php

//common environment attributes including search paths. not specific to Learnosity
include_once '../env_config.php';

//site scaffolding
include_once 'includes/header.php';

//common Learnosity config elements including API version control vars
include_once '../lrn_config.php';

use LearnositySdk\Request\Init;
use LearnositySdk\Utils\Uuid;

$security = [
    'consumer_key' => $consumer_key,
    'domain'       => $domain
];


//simple api request object for item list view, with optional creation of items
$request = [
    'mode'      => 'item_edit',
    'reference' => Uuid::generate(),
    'config'    => [
        'item_edit' => [
            'item' => [
                'columns' => true,
                'save' => false,
                'status' => false,
                'reference' => [
                    'edit' => false,
                    'show' => false
                ],
                'mode' => [
                    'default' => 'edit',
                    'show' => true
                ]
            ],
            'widget' => [
                'delete' => true,
                'edit' => true
            ]
        ],
        'widget_templates' => [
            'back' => true,
            'save' => true,
            'widget_types' => [
                'default' => 'features',
                'show' => true,
            ],
        ],
        'dependencies' => [
            'question_editor_api' => [
                'version' => $version_questioneditorapi,
                'init_options' => [
                    'ui'=> [
                        'layout'=> [
                             'global_template'=> 'edit_preview'
                        ]
                    ],
                    'custom_feature_types'=> [[
                        'custom_type'=> 'simplegallery',
                        'name'=> 'Custom Image Gallery',
                        'js'=> "https://demos.vg.learnosity.com/authoring/customfeature/simplegallery.js",
                        'css'=> "https://demos.vg.learnosity.com/authoring/customfeature/simplegallery.css",
                        'version'=> 'v0.0.1',
                        'editor_layout'=> "https://demos.vg.learnosity.com/authoring/customfeature/simplegallery.html",
                        'editor_schema'=> [
                            'hidden_question'=> false,
                            'properties'=> [
                                'photos'=>[
                                    'name'=> 'Photos',
                                    'description'=> 'Photos',
                                    'type'=> 'array',
                                    'required'=> true,
                                    'items'=> [
                                        'name'=> 'Photo',
                                        'type'=> 'imageObject',
                                        'attributes'=> [
                                            'source'=> [
                                                'name'=> 'Add image',
                                                'description'=> 'The image that should be displayed.',
                                                'type'=> 'string',
                                                'required'=> true,
                                                'asset'=> [
                                                    'mediaType'=> 'image',
                                                    'returnType'=> 'URL'
                                                ]
                                            ],
                                            'alt'=> [
                                                'name'=> 'Image alternative text',
                                                'description'=> 'The alternative text of the image.',
                                                'type'=> 'string',
                                                'required'=> false
                                            ],
                                            'credit'=> [
                                                'name'=> 'Image Credit',
                                                'description'=> 'The Credit text of the image.',
                                                'type'=> 'string',
                                                'required'=> false
                                            ]
                                        ]
                                    ],
                                    'default'=> [
                                        ['source'=> "https://demos.vg.learnosity.com/authoring/customfeature/newstandard.png","alt"=>"photo 1", "credit"=>"Learnosity"],
                                        ['source'=> "https://demos.vg.learnosity.com/authoring/customfeature/savetime.png","alt"=>"photo 2", "credit"=>"Learnosity"],
                                        ['source'=> "https://demos.vg.learnosity.com/authoring/customfeature/alwaysmovingfwd.png","alt"=>"photo 3", "credit"=>"Learnosity"],
                                        ['source'=> "https://demos.vg.learnosity.com/authoring/customfeature/seamlessintegration.png","alt"=>"photo 4", "credit"=>"Learnosity"]
                                    ]
                                ]
                            ]
                    ]]]
                ]
            ]
        ]
    ],
    'user' => array(
        'id'        => 'demos-site',
        'firstname' => 'Demos',
        'lastname'  => 'User',
        'email'     => 'demos@learnosity.com'
    )
];

$Init = new Init('author', $security, $consumer_secret, $request);
$signedRequest = $Init->generate();

?>

    <div class="jumbotron section">
        <div class="toolbar">
            <ul class="list-inline">
                <li data-toggle="tooltip" data-original-title="Preview API Initialisation Object"><a href="#"  data-toggle="modal" data-target="#initialisation-preview"><span class="glyphicon glyphicon-search"></span></a></li>
                <li data-toggle="tooltip" data-original-title="Visit the documentation"><a href="http://docs.learnosity.com/authorapi/" title="Documentation"><span class="glyphicon glyphicon-book"></span></a></li>
            </ul>
        </div>
        <div class="overview">
            <h2>Authoring Custom Feature</h2>
            <p>
                Set up the editor layout using the Author API to author Custom Features. In this demo, we've added a Custom Image Gallery Feature where you can add/remove and navigate through images.                                
            </p>
        </div>
    </div>

    <div class="section pad-sml">
        <!-- Container for the author api to load into -->
        <div id="learnosity-author"></div>
    </div>

    <script src="<?php echo $url_authorapi; ?>"></script>
    <script>
        var initializationObject = <?php echo $signedRequest; ?>;

        //optional callbacks for ready
        var callbacks = {
            readyListener: function () {
                console.log("Author API has successfully initialized.");
            },
            errorListener: function (err) {
                console.log(err);
            }
        };

        var authorApp = LearnosityAuthor.init(initializationObject, callbacks);
    </script>

<?php
include_once 'views/modals/initialisation-preview.php';
include_once 'includes/footer.php';
