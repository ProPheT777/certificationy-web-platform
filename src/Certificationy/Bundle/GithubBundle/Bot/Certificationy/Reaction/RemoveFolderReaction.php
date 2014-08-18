<?php
/**
* This file is part of the PhpStorm.
* (c) johann (johann_27@hotmail.fr)
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
**/

namespace Certificationy\Bundle\GithubBundle\Bot\Certificationy\Reaction;

use Certificationy\Bundle\GithubBundle\Bot\Certificationy\Action\RemoveFolderAction;
use Certificationy\Bundle\GithubBundle\Bot\Common\LoggerTrait;
use Certificationy\Bundle\GithubBundle\Bot\Common\Reaction\LoggableReactionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class RemoveFolderReaction implements LoggableReactionInterface
{
    use CheckReactionTrait, LoggerTrait;

    /**
     * @param RemoveFolderAction $action
     *
     * @throws \RuntimeException
     */
    public function perform(RemoveFolderAction $action)
    {
        $content = $action->getData()['content'];

        $cmd = [];
        $cmd[] = 'cd analyze';
        $cmd[] = 'rm -Rf '.$content['pull_request']['head']['sha'];

        $process = new Process(implode(' && ', $cmd));
        $process->setTimeout(50);
        $process->run();

        if (!$process->isSuccessful()) {
            if (null !== $this->logger) {
                $this->logger->error($process->getErrorOutput());
            }

            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
