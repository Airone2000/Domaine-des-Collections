<?php

namespace App\Enum;

class PublicationStatus
{
    const PUBLISHED = 'published';
    const DRAFT = 'draft';
    const OFFLINE_BECAUSE_INVALID = 'offline_because_invalid';
    const OFFLINE = 'offline';
}