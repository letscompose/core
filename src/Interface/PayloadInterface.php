<?php

namespace LetsCompose\Core\Interface;

interface PayloadInterface
{
    public function setPayload(mixed $payload): self;
    public function getPayload(): mixed;
}