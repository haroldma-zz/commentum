<?php

function hashID($id)
{
	return Vinkla\Hashids\Facades\Hashids::encode($id);
}