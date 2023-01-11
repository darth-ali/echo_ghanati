<?php


namespace BaseModel;

use UserManagement\Role;

class Step extends Type
{


    private int   $controlledStepID; // این برای زمانی است که
    private array $rolesAccessStep; // string array contain roles Slug
    private bool  $acceptanceCapability;

    /**
     * FeedStep constructor.
     * @param int $ID
     * @param string $name
     * @param int $controlledStepID
     * @param string[] $rolesAccessStep
     * @param string $slug
     * @param string $description
     * @param bool $acceptanceCapability
     */
    private function __construct(int $ID = 0, string $name = '', string $slug = '', string $description = '', int $controlledStepID = 0, array $rolesAccessStep = ['administrator', 'supervisor'], bool $acceptanceCapability = false)
    {
        parent::__construct($ID, $name, $slug, $description);
        $this->controlledStepID = $controlledStepID;
        $this->rolesAccessStep = $rolesAccessStep;
        $this->acceptanceCapability = $acceptanceCapability;
    }

    // region Class Public Functions::

    /**
     * @return Role[]
     */
    public function getRolesAccessStep(): array
    {
        $roles = [];
        foreach ($this->rolesAccessStep as $roleSlug) {
            $role = Role::Create($roleSlug);
            if ($role)
                $roles[] = $role;
        }
        return $roles;
    }

    /**
     * @return Step | false
     */
    public function getControlledStep()
    {
        return ($this->controlledStepID == 0) ? false : new Step($this->controlledStepID);

    }

    /**
     * @return bool
     */
    public function getAcceptanceCapability(): bool
    {
        return $this->acceptanceCapability;
    }
    // //endregion


    // region Class Static Functions::

    /**
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $description
     * @param int $controlledStepID
     * @param string[] $rolesAccessStep
     * @param bool $acceptanceCapability
     * @return Step
     */
    public static function Create(int $ID, string $name, string $slug = '', string $description = '', int $controlledStepID = 0, array $rolesAccessStep = ['administrator', 'supervisor'], bool $acceptanceCapability = false): Step
    {
        return new self($ID, $name, $slug, $description, $controlledStepID, $rolesAccessStep, $acceptanceCapability);
    }

    /**
     * @param int $ID
     * @return false|Step
     */
    public static function Get(int $ID)
    {
        global $taskSteps;
        if (key_exists($ID, $taskSteps))
            return $taskSteps[$ID];
        else
            return false;
    }
    //endregion

}
