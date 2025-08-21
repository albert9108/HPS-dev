<?php

use Alexusmai\LaravelFileManager\Services\ConfigService\DefaultConfigRepository;
use App\Services\FileManagerACLRepository;

return [
    /**
     * Set Config repository
     *
     * Default - DefaultConfigRepository get config from this file
     */
    'configRepository'  => DefaultConfigRepository::class,

    /**
     * ACL rules repository
     *
     * Default - ConfigACLRepository (see rules in - aclRules)
     */
    'aclRepository'     => FileManagerACLRepository::class,

    //********* Default configuration for DefaultConfigRepository **************

    /**
     * LFM Route prefix
     * !!! WARNING - if you change it, you should compile frontend with new prefix(baseUrl) !!!
     */
    'routePrefix'       => 'filemanager',

    /**
     * List of disk names that you want to use
     * (from config/filesystems)
     */
    'diskList'          => ['public'],

    /**
     * Default disk for left manager
     *
     * null - auto select the first disk in the disk list
     */
    'leftDisk'          => null,

    /**
     * Default disk for right manager
     *
     * null - auto select the first disk in the disk list
     */
    'rightDisk'         => null,

    /**
     * Default path for left manager
     *
     * null - root directory
     */
    'leftPath'          => null,

    /**
     * Default path for right manager
     *
     * null - root directory
     */
    'rightPath'         => null,

    /**
     * File manager modules configuration
     *
     * 1 - only one file manager window
     * 2 - one file manager window with directories tree module
     * 3 - two file manager windows
     */
    'windowsConfig'     => 2,

    /**
     * File upload - Max file size in KB
     *
     * null - no restrictions
     */
    'maxUploadFileSize' => 10240, // 10MB limit

    /**
     * File upload - Allow these file types
     *
     * [] - no restrictions
     */
    'allowFileTypes'    => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar'],

    /**
     * Show / Hide system files and folders
     */
    'hiddenFiles'       => true,

    /***************************************************************************
     * Middleware
     *
     * Add your middleware name to array -> ['web', 'auth', 'admin']
     * !!!! RESTRICT ACCESS FOR NON ADMIN USERS !!!!
     */
    'middleware'        => ['web', 'auth'],

    /***************************************************************************
     * ACL mechanism ON/OFF
     *
     * default - false(OFF)
     */
    'acl'               => true,

    /**
     * Hide files and folders from file-manager if user doesn't have access
     *
     * ACL access level = 0
     */
    'aclHideFromFM'     => true,

    /**
     * ACL strategy
     *
     * blacklist - Allow everything(access - 2 - r/w) that is not forbidden by the ACL rules list
     *
     * whitelist - Deny anything(access - 0 - deny), that not allowed by the ACL rules list
     */
    'aclStrategy'       => 'whitelist',

    /**
     * ACL Rules cache
     *
     * null or value in minutes
     */
    'aclRulesCache'     => 30,

    //********* Default configuration for DefaultConfigRepository END **********


    /***************************************************************************
     * ACL rules list - used for default ACL repository (ConfigACLRepository)
     *
     * 1 it's user ID
     * null - for not authenticated user
     *
     * 'disk' => 'disk-name'
     *
     * 'path' => 'folder-name'
     * 'path' => 'folder1*' - select folder1, folder12, folder1/sub-folder, ...
     * 'path' => 'folder2/*' - select folder2/sub-folder,... but not select folder2 !!!
     * 'path' => 'folder-name/file-name.jpg'
     * 'path' => 'folder-name/*.jpg'
     *
     * * - wildcard
     *
     * access: 0 - deny, 1 - read, 2 - read/write
     */
    'aclRules'          => [
        // Non-authenticated users - no access
        null => [],

        // Admin users (role-based access will be handled by custom ACL)
        // Students (role-based access will be handled by custom ACL)
    ],

    /**
     * Enable slugification of filenames of uploaded files.
     *
     */
    'slugifyNames'      => true,
];
