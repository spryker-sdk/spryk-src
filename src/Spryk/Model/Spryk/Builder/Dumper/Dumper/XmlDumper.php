<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Builder\Dumper\Dumper;

use DOMDocument;

class XmlDumper implements XmlDumperInterface
{
    /**
     * @param array<\SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface> $resolvedFiles
     *
     * @return void
     */
    public function dump(array $resolvedFiles): void
    {
        foreach ($resolvedFiles as $resolvedFile) {
            $dom = new DOMDocument('1.0');
            $dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;
            $dom->encoding = 'UTF-8';

            $dom->loadXML((string)$resolvedFile->getSimpleXmlElement()->asXML());

            $dom->recover = true;

            $xmlString = (string)$dom->saveXML();

            // Add a new line after each transfer definition and...
            $xmlString = preg_replace_callback('/(<\/transfer>)/', function ($matches) {
                return $matches[1] . "\n";
            }, $xmlString);

            // replace two with four spaces to get back the original formatting (as close as possible)
            $xmlString = str_replace('  ', '    ', (string)$xmlString);

            $resolvedFile->setContent($xmlString);
        }
    }
}
