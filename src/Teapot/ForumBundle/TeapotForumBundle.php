<?php

namespace Teapot\ForumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TeapotForumBundle extends Bundle
{
	public function getParent()
	{
		return 'TeapotBaseForumBundle';
	}
}
