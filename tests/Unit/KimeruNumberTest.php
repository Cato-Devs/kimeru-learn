<?php

namespace Tests\Unit;

use App\Support\KimeruNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class KimeruNumberTest extends TestCase
{
    public function test_it_builds_the_kimeru_words_for_1_to_100(): void
    {
        $expected = [
            1 => 'ĩmwe',
            2 => 'iyĩrĩ',
            9 => 'kenda',
            10 => 'ĩkũmi',
            11 => 'ĩkũmi na ĩmwe',
            12 => 'ĩkũmi na iyĩrĩ',
            19 => 'ĩkũmi na kenda',
            20 => 'mĩrongo ĩrĩ',
            22 => 'mĩrongo ĩrĩ na iyĩrĩ',
            40 => 'mĩrongo ĩna',
            57 => 'mĩrongo ĩthano na mũgwanja',
            60 => 'mĩrongo ĩthatathũ',
            66 => 'mĩrongo ĩthatathũ na ithanthatũ',
            78 => 'mĩrongo mũgwanja na inyanya',
            79 => 'mĩrongo mũgwanja na kenda',
            80 => 'mĩrongo ĩnaana',
            99 => 'mĩrongo kenda na kenda',
            100 => 'ĩgana',
        ];

        foreach ($expected as $n => $words) {
            $this->assertSame($words, KimeruNumber::toWords($n), "Wrong words for $n");
        }
    }

    public function test_every_number_from_1_to_100_has_different_words(): void
    {
        $all = array_map(fn ($n) => KimeruNumber::toWords($n), range(1, 100));

        $this->assertCount(100, array_unique($all));
    }

    public function test_numbers_outside_1_to_100_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        KimeruNumber::toWords(0);
    }
}
