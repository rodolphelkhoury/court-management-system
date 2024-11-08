<?php

namespace App\Domain\CourtType\Enums;

enum CourtTypeName: string
{
    case TENNIS = 'tennis';
    case BASKETBALL = 'basketball';
    case VOLLEYBALL = 'volleyball';
    case BADMINTON = 'badminton';
    case SQUASH = 'squash';
    case TABLE_TENNIS = 'table_tennis';
    case SOCCER = 'soccer';
    case FUTSAL = 'futsal';
    case HANDBALL = 'handball';
    case NETBALL = 'netball';
    case PICKLEBALL = 'pickleball';
    case HOCKEY_FIELD = 'hockey_field';
    case HOCKEY_ROLLER = 'hockey_roller';
    case CRICKET_PRACTICE = 'cricket_practice';
    case MULTISPORT = 'multi_sport';
    case PADEL = 'padel';
    case RACQUETBALL = 'racquetball';
}