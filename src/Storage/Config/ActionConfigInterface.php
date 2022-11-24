<?php
/*
 * This file is part of the LestCompose/Core package.
 *
 * (c) Igor ZLOBINE <izlobine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LetsCompose\Core\Storage\Config;

use LetsCompose\Core\Storage\Resource\ResourceInterface;

/**
 * @author Igor ZLOBINE <izlobine@gmail.com>
 */
interface ActionConfigInterface
{
  public function setName(string $string): self;

  public function getName(): self;

  public function setNameSpace(string $string): self;
  
  public function getNameSpace(): string;

  public function setClass(string $string): string;

  public function getClass(): self;
}