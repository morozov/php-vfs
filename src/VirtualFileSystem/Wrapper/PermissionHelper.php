<?php

namespace VirtualFileSystem\Wrapper;


use VirtualFileSystem\Structure\Node;

class PermissionHelper
{
    const MODE_USER_READ = 0400;
    const MODE_USER_WRITE = 0200;

    const MODE_GROUP_READ = 0040;
    const MODE_GROUP_WRITE = 0020;

    const MODE_WORLD_READ = 0004;
    const MODE_WORLD_WRITE = 0002;

    /**
     * @var Node
     */
    protected $node;

    protected $userid;
    protected $groupid;

    /**
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
        $this->userid = posix_getuid();
        $this->groupid = posix_getgid();
    }

    /**
     * Checks whether user_id on file is the same as executing user
     *
     * @return bool
     */
    public function userIsOwner()
    {
        return (bool)($this->userid == $this->node->user());
    }

    /**
     * Checks whether file is readable for user
     *
     * @return bool
     */
    public function userCanRead()
    {
        return $this->userIsOwner() && ($this->node->mode() & self::MODE_USER_READ);
    }

    /**
     * Checks whether file is writable for user
     *
     * @return bool
     */
    public function userCanWrite()
    {
        return $this->userIsOwner() && ($this->node->mode() & self::MODE_USER_WRITE);
    }

    /**
     * Checks whether group_id on file is the same as executing user
     *
     * @return bool
     */
    public function groupIsOwner()
    {
        return (bool)($this->groupid == $this->node->group());
    }

    /**
     * Checks whether file is readable for group
     *
     * @return bool
     */
    public function groupCanRead()
    {
        return $this->groupIsOwner() && ($this->node->mode() & self::MODE_GROUP_READ);
    }

    /**
     * Checks whether file is writable for group
     *
     * @return bool
     */
    public function groupCanWrite()
    {
        return $this->groupIsOwner() && ($this->node->mode() & self::MODE_GROUP_WRITE);
    }

    /**
     * Checks whether file is readable for world
     *
     * @return bool
     */
    public function worldCanRead()
    {
        return (bool)($this->node->mode() & self::MODE_WORLD_READ);
    }

    /**
     * Checks whether file is writable for world
     *
     * @return bool
     */
    public function worldCanWrite()
    {
        return (bool)($this->node->mode() & self::MODE_WORLD_WRITE);
    }

    /**
     * Checks whether file is readable (by user, group or world)
     *
     * @return bool
     */
    public function isReadable()
    {
        return $this->userCanRead() || $this->groupCanRead() || $this->worldCanRead();
    }

    /**
     * Checks whether file is writable (by user, group or world)
     *
     * @return bool
     */
    public function isWritable()
    {
        return $this->userCanWrite() || $this->groupCanWrite() || $this->worldCanWrite();
    }
}