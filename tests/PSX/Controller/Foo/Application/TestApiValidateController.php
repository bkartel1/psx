<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace PSX\Controller\Foo\Application;

use PSX\Controller\ApiAbstract;
use PSX\Filter;
use PSX\Http\Message;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\Validator;

/**
 * TestApiValidateController
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class TestApiValidateController extends ApiAbstract
{
    /**
     * @Inject
     * @var \PHPUnit_Framework_TestCase
     */
    protected $testCase;

    /**
     * @Inject
     * @var \PSX\Data\Schema\SchemaManager
     */
    protected $schemaManager;

    /**
     * @Inject
     * @var \PSX\Data\Importer
     */
    protected $importer;

    public function doIndex()
    {
        $this->setBody([
            'foo' => 'bar'
        ]);
    }

    public function doInsert()
    {
        $data = $this->import($this->schemaManager->getSchema('PSX\Controller\Foo\Schema\NestedEntry'));

        $this->testCase->assertInstanceOf('PSX\Data\RecordInterface', $data);

        // we check that the validator is only applied for the request. If the 
        // importer manager is not immutable the importer would also have the 
        // request validator
        $message = new Message([], '{"title": "foofoofoo"}');
        $data    = $this->importer->import($this->schemaManager->getSchema('PSX\Controller\Foo\Schema\NestedEntry'), $message);

        $this->setBody([
            'success' => true,
        ]);
    }

    protected function getImportValidator()
    {
        return new Validator([
            new Property('/title', Validate::TYPE_STRING, [new Filter\Length(3, 8)]),
            new Property('/author/name', Validate::TYPE_STRING, [new Filter\Length(3, 8)]),
        ]);
    }
}