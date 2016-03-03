<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2016 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Api\Documentation;

use PSX\Api\DocumentationInterface;
use PSX\Api\Resource;

/**
 * Explicit
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Explicit implements DocumentationInterface
{
    protected $version;
    protected $resource;
    protected $description;

    public function __construct($version, Resource $resource, $description = null)
    {
        $this->version     = (int) $version;
        $this->resource    = $resource;
        $this->description = $description;
    }

    public function hasResource($version)
    {
        return $version == $this->version;
    }

    public function getResource($version)
    {
        return $version == $this->version ? $this->resource : null;
    }

    public function getResources()
    {
        return [$this->version => $this->resource];
    }

    public function getLatestVersion()
    {
        return $this->version;
    }

    public function isVersionRequired()
    {
        return false;
    }

    public function getDescription()
    {
        return $this->description;
    }
}