<?php

namespace App\Enums;

enum CourseStatus: string
{
    case ENROLLED = 'enrolled';

    case IN_PROGRESS = 'in progress';

    case COMPLETED = 'completed';

    case DROPPED = 'dropped';
}
