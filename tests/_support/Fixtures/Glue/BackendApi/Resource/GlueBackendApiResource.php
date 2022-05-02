<?php

namespace SprykerTest;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;

class GlueBackendApiResource
{
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return (new GlueResourceMethodCollectionTransfer());
    }
}
