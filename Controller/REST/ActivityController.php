<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Activity\EZPlatformBundle\Controller\REST;

use CampaignChain\CoreBundle\Controller\REST\BaseController;
use CampaignChain\CoreBundle\Entity\Activity;
use CampaignChain\CoreBundle\Entity\Module;
use FOS\RestBundle\Controller\Annotations as REST;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends BaseController
{
    /**
     * Schedule an eZ Platform content object.
     *
     * Example Request
     * ===============
     *
     *      POST /api/v1/p/campaignchain/activity-ezplatform/activities
     *
     * Example Response
     * ================
     *
    {
    "response": {
    "1": "http://wordpress.amariki.com",
    "2": "http://www.slideshare.net/amariki_test",
    "3": "https://global.gotowebinar.com/webinars.tmpl",
    "4": "https://twitter.com/AmarikiTest1",
    "5": "https://www.facebook.com/pages/Amariki/1384145015223372",
    "6": "https://www.facebook.com/profile.php?id=100008874400259",
    "7": "https://www.facebook.com/profile.php?id=100008922632416",
    "8": "https://www.linkedin.com/pub/amariki-software/a1/455/616"
    }
    }
     *
     * @ApiDoc(
     *  section="Packages: eZ Platform"
     * )
     *
     * @REST\Post()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function scheduleObjectsAction(Request $request)
    {
//        $data = array(
//            'campaignchain_core_channel' => array(
//                'campaignchain_core_location' => array(
//                    'id' => 100,
//                )
//            ),
//            'campaignchain_core_campaign' => array(
//                'id' => 1,
//            ),
//            'campaignchain_core_activity' => array(
//                'name' => 'About eZ',
//                'campaignchain_hook_campaignchain_due' => array(
//                    'date' => '2015-12-20T12:00:00+0000', // Throw error if not within campaign duration.
//                ),
//                'campaignchain_hook_campaignchain_assignee' => array(
//                    'user' => 1,
//                )
//            )
//        );

        try {
            $data = json_decode($request->getContent());

            // Get the Channel object.
            $locationService = $this->get('campaignchain.core.location');
            $location = $locationService->getLocation($data->campaignchain_core_channel->campaignchain_core_location->id);

            // Get the Campaign object.
            $campaignService = $this->get('campaignchain.core.campaign');
            $campaign = $campaignService->getCampaign($data->campaignchain_core_campaign->id);

            $activityModuleService = $this->get(
                'campaignchain.activity.controller.campaignchain.ezplatform.schedule'
            );

            $activityModuleService->setActivityContext($campaign, $location);

            $activity = new Activity();
            $activity->setName($data->campaignchain_core_activity->name);

            $form = $this->createForm(
                $activityModuleService->getActivityFormType('rest'), $activity
            );

            $form->handleRequest($request);

            if ($form->isValid()) {
                $activity = $activityModuleService->createActivity($activity, $form);

                $routeOptions = array(
                    'id' => $activity->getId(),
                    'version' => 'v1',
                    '_format' => $request->get('_format')
                );

                $view = $this->routeRedirectView(
                    'campaignchain_core_rest_activity_get_activities_activity',
                    $routeOptions,
                    Response::HTTP_CREATED
                );
                //print_r($routeOptions);die();
                return $this->handleView($view);
            } else {
                return $this->errorResponse(
                    $form
                );
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}