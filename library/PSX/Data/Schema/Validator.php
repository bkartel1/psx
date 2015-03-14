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

namespace PSX\Data\Schema;

use PSX\Data\RecordInterface;
use PSX\Data\SchemaInterface;
use PSX\Data\Schema\Property;
use PSX\Data\Schema\PropertyInterface;

/**
 * Validator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Validator implements ValidatorInterface
{
	public function validate(SchemaInterface $schema, $data)
	{
		$this->recValidate($schema->getDefinition(), $data);

		return true;
	}

	protected function recValidate(PropertyInterface $type, $data, $path = '$')
	{
		if($type instanceof Property\ComplexType)
		{
			if(!$data instanceof \stdClass)
			{
				throw new ValidationException('Data object expected at ' . $path);
			}

			$type->validate($data);

			$properties = $type->getProperties();

			foreach($properties as $name => $property)
			{
				if(isset($data->$name))
				{
					$this->recValidate($property, $data->$name, $path . '.' . $name);
				}
				else if($property->isRequired())
				{
					throw new ValidationException('Required property ' . $path . '.' . $property->getName() . ' not available');
				}
			}
		}
		else if($type instanceof Property\ArrayType)
		{
			if(!is_array($data))
			{
				throw new ValidationException('Data array expected at ' . $path);
			}

			$type->validate($data);

			$prototype = $type->getPrototype();

			foreach($data as $key => $value)
			{
				$this->recValidate($prototype, $value, $path . '[' . $key . ']');
			}
		}
		else
		{
			$type->validate($data);
		}
	}
}
