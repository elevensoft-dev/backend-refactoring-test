<?php

namespace Tests\Unit;

use App\Models\User;

use Tests\TestCase;

class UserModelTest extends TestCase
{

    public function testForUserStore(): void
     {
        $userFactory = User::factory()->raw();
        $user = User::create($userFactory);
        $arrUser = json_decode($user,true);

        $this->assertArrayHasKey('id', $arrUser);
        $this->assertArrayHasKey('name', $arrUser);
        $this->assertArrayHasKey('email', $arrUser);
        $this->assertEquals(5, count($arrUser));

     }

     public function testForUserEditById(): void
     {
        $userFactory = User::factory()->raw();
        $userIns = User::create($userFactory);
        $user = User::where('email', $userIns['email'])->first();
        $arrUser = json_decode($user,true);

        $this->assertArrayHasKey('id', $arrUser);
        $this->assertArrayHasKey('name', $arrUser);
        $this->assertArrayHasKey('email', $arrUser);
        $this->assertEquals(7, count($arrUser));

     }

     public function testForUserUpdate(): void
     {
        $userFactory = User::factory()->raw();

        $userIns = User::create($userFactory);
        $arrUserIns = json_decode($userIns,true);

        $user = User::find($arrUserIns['id']);
        $arrUserIns['name'] = $userIns['name'] . ' - Update';
        unset($arrUserIns['id']);
        $user->update($arrUserIns);

        $arrUser = json_decode($user,true);

        $this->assertArrayHasKey('id', $arrUser);
        $this->assertArrayHasKey('name', $arrUser);
        $this->assertArrayHasKey('email', $arrUser);
        $this->assertEquals(7, count($arrUser));

     }

     public function testForUserDelete(): void
     {
        $userFactory = User::factory()->raw();

        $userIns = User::create($userFactory);
        $arrUserIns = json_decode($userIns,true);

        $user = User::find($arrUserIns['id']);
        $value = $user->delete();

        $this->assertNotNull($value);
        $this->assertTrue($value);

     }

}
