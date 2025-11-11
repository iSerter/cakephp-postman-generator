Package structure (placed under plugins or installed via composer):

plugins/CakephpPostmanGenerator/
├── composer.json
├── README.md
├── src/
│   ├── Plugin.php
│   ├── Command/
│   │   └── GeneratePostmanCollectionCommand.php
│   └── Utility/
│       ├── RouteExtractor.php
│       └── PostmanCollectionBuilder.php
└── config/
    └── postman.php

After installing, enable the plugin and run:
bin/cake iserter_postman generate -o /path/to/my-api.postman_collection.json
