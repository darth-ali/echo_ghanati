<?php


namespace UserManagement;
class Role
{
    private string $name;
    private string $slug;

    /**
     * Term constructor.
     * @param string $slug
     */
    private function __construct(string $slug)
    {
        $this->name = wp_roles()->roles[$slug]['name'];
        $this->slug = $slug;

    }


    // region Class Getter Functions::

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    //endregion

    // region Class Static Functions::

    /**
     * @param string $slug
     * @return false|Role
     */
    public static function Create(string $slug = '')
    {
        if (array_key_exists($slug, wp_roles()->roles))
            return new self($slug);
        return false;
    }

    /**
     * @param int $userID
     * @return Role[]
     */
    public static function GetUserRoles(int $userID): array
    {
        $result = [];
        $userRoles = get_userdata($userID)->roles;
        if ($userRoles) {
            foreach ($userRoles as $roleSlug) {
                $role = Role::Create($roleSlug);
                if ($role)
                    $result[] = $role;
            }
        }
        return $result;
    }

    /**
     * @return Role[]
     */
    public static function GetAllRoles(): array
    {
        $roles = wp_roles()->roles;
        $result = [];

        foreach ($roles as $roleSlug => $roleObject) {
            $result[] = new self($roleSlug);
        }
        return $result;
    }
    //endregion

}