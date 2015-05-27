<?php
namespace AppBundle\Service\Api\Listing;

interface ListingServiceDocumentInterface
{
	public function getId();
	public function setUserId($user_id);
	public function getUserId();
}