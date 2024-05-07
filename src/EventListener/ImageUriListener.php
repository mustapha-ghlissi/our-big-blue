<?php

namespace App\EventListener;

use App\Entity\Image;
use Vich\UploaderBundle\Event\Event;

class ImageUriListener
{
    /**
     * @param Event $event
     * @return void
     */
    public function onVichUploaderPostUpload(Event $event): void
    {
        /**
         * @var  Image $object
         */
        $object = $event->getObject();

        $object->uri = '/uploads/images/' . $object->uri;
    }
}
