<?php
namespace AOE\AoeIpauth\EventListener;

use TYPO3\CMS\Frontend\Authentication\ModifyResolvedFrontendGroupsEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AddIpGroupToFrontendAuthentification
{
    public function __invoke(ModifyResolvedFrontendGroupsEvent $event): void {
        /** @var \TYPO3\CMS\Core\Http\NormalizedParams $normalizedParams */
        $normalizedParams = $event->getRequest()->getAttribute('normalizedParams');
        $requestIp = $normalizedParams->getRemoteAddress();
        $ipGroups = $this->getFeEntityService()->findAllGroupsAuthenticatedByIp($requestIp);
        $event->setGroups($event->getGroups() + $ipGroups);
    }
    
    /**
     * @return \AOE\AoeIpauth\Domain\Service\FeEntityService
     */
    protected function getFeEntityService()
    {
        if (null === $this->feEntityService) {
            $this->feEntityService = GeneralUtility::makeInstance(\AOE\AoeIpauth\Domain\Service\FeEntityService::class);
        }
        return $this->feEntityService;
    }
    
}

