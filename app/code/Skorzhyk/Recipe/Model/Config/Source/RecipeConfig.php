<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model\Config\Source;

/**
 * Class RecipeConfig
 * @package Skorzhyk\Recipe\Model\Config\Source
 */
class RecipeConfig
{
    /**
     * @var string
     */
    const MEDIA_RECIPE_FOLDER = 'recipes';

    /**
     * @var string
     */
    const MEDIA_RECIPE_TMP_FOLDER = 'recipes/tmp';

    /**
     * @var int
     */
    const NOT_VALIDATE_STATUS = 0;

    /**
     * @var int
     */
    const VALIDATE_STATUS = 1;

    /**
     * @var string
     */
    const RECIPE_SAVE_URL = 'recipe/index/save';

    /**
     * @var string
     */
    const RECIPE_REMOVE_URL = 'recipe/index/remove';
}
