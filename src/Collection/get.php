<?php
declare(strict_types=1);

namespace _;

/**
 * Gets the value at path of object. If the resolved value is undefined, the defaultValue is returned in its place.
 *
 * @category Collection
 *
 * @param    mixed The object to query
 * @param    string The path of the property to get.
 * @param    any The value to return if property could not be found
 *
 * @return mixed the property at path or the defaultValue value
 *
 * @example
 * <code>
 * get(object, 'a[0].b.c');
 * // => 3
 *
 * get(object, ['a', '0', 'b', 'c']);
 * // => 3
 *
 * get(object, 'a.b.c', 'default');
 * // => 'default'
 * </code>
 */
function get($object, $path, $defaultValue = null)
{
    if (empty($path)) {
        return $object;
    }

    if (is_callable($defaultValue)) {
        $default = $defaultValue;
    } else {
        $default = function() use ($defaultValue) {
            return $defaultValue;
        };
    }

    if (is_string($path)) {
        $tokens = preg_split('/[\[\]\{\}.]+/', $path, -1, PREG_SPLIT_NO_EMPTY);
    } else if (is_array($path)) {
        $tokens = $path;
    } else {
        return $default();
    }

    foreach ($tokens as $token) {
        if (is_object($object)) {
            if (!isset($object->{$token})) {
                return $default();
            } else {
                $object = $object->{$token};
            }
        } else if (is_array($object)) {
            if (!isset($object[$token])) {
                return $default();
            } else {
                $object = $object[$token];
            }
        } else {
            return $default();
        }
    }

    return $object;
}
