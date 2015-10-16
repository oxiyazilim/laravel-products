<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Products\Events\AttributeWasDestroyed;
use Speelpenning\Products\Events\AttributeWasStored;
use Speelpenning\Products\Events\AttributeWasUpdated;
use Speelpenning\Products\Jobs\DestroyAttribute;
use Speelpenning\Products\Jobs\StoreAttribute;
use Speelpenning\Products\Jobs\UpdateAttribute;

class AttributeJobsTest extends TestCase
{
    use DispatchesJobs;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish');
        $this->artisan('migrate:refresh');
    }

    protected function storeAttribute($description, $type)
    {
        return $this->dispatchFromArray(StoreAttribute::class, compact('description', 'type'));
    }


    public function testStoreAttribute()
    {
        $description = 'Length';
        $type = 'numeric';
        $this->expectsEvents(AttributeWasStored::class);

        $attribute = $this->storeAttribute($description, $type);

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertNotNull($attribute->id);
        $this->assertEquals($description, $attribute->description);
        $this->assertEquals($type, $attribute->type);

        $this->seeInDatabase('attributes', compact('description', 'type'));
    }

    public function testUpdateAttribute()
    {
        $id = $this->storeAttribute('Length', 'numeric')->id;
        $description = 'Width';

        $this->expectsEvents(AttributeWasUpdated::class);

        $attribute = $this->dispatchFromArray(UpdateAttribute::class, compact('id', 'description'));

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertEquals($description, $attribute->description);

        $this->seeInDatabase('attributes', compact('description'));
    }

    public function testDestroyAttribute()
    {
        $id = $this->storeAttribute('Attribute to be destroyed', 'string')->id;
        $this->expectsEvents(AttributeWasDestroyed::class);

        $attribute = $this->dispatchFromArray(DestroyAttribute::class, compact('id'));

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertNotNull($attribute->deleted_at);
    }
}