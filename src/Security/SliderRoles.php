<?php

namespace TwinElements\SliderBundle\Security;

use TwinElements\AdminBundle\Role\RoleGroupInterface;

final class SliderRoles implements RoleGroupInterface
{
    const ROLE_SLIDER_FULL = 'ROLE_SLIDER_FULL';
    const ROLE_SLIDER_EDIT = 'ROLE_SLIDER_EDIT';
    const ROLE_SLIDER_VIEW = 'ROLE_SLIDER_VIEW';

    public static function getRoles(): array
    {
        return [self::ROLE_SLIDER_FULL, self::ROLE_SLIDER_EDIT, self::ROLE_SLIDER_VIEW];
    }
}
