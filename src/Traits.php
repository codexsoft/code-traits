<?php /** @noinspection SlowArrayOperationsInLoopInspection */

namespace CodexSoft\Code\Traits;

class Traits
{

    /**
     * @param $class
     * @param $trait
     *
     * @return bool
     */
    public static function isUsedBy($class, $trait): bool
    {
        if ( \is_object( $class ) ) $class = \get_class($class);
        return \in_array( $trait, self::usedByClass( $class ), true );
    }

    /**
     * To get ALL traits including those used by parent classes and other traits,
     * also checks all the "parent" traits used by the traits:
     *
     * @param $class
     * @param bool|true $autoload
     *
     * @return string[]
     */
    public static function usedByClass($class, $autoload = true): array
    {

        $traits = [];

        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        };

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }

    /**
     * To get ALL traits including those used by parent classes and other traits
     *
     * @param $class
     * @param bool|true $autoload
     *
     * @return string[]
     */
    public static function classUsesDeep( $class, $autoload = true): array
    {
        $traits = [];
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while($class = get_parent_class($class));
        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }
        return array_unique($traits);
    }


}
