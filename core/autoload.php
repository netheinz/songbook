<?php
/**
 * @Description of classloader
 * Uses the Standard PHP Library (SPL) function spl_autoload_register
 *
 * IMPORTANT NOTICE!!!
 * - All class files must be placed in directory /core/classes/
 * - All class files must match the class name with small caps (Ex: song.php, artist.php etc.)
 */
spl_autoload_register(function ($class) {
    include COREROOT . '/classes/' . strtolower($class). '.php';
});