<?php

namespace App\Core\Service;

class YoutubeService
{
    /** @var \Google_Client */
    private $client;
    /** @var \Google_Service_YouTube */
    private $service;

    private function initService()
    {
        if (null === $this->client) {
            $this->client = new \Google_Client();
        }

        $this->client->setDeveloperKey(getenv('APP_DEVELOPER_KEY'));

        if (null === $this->service) {
            $this->service = new \Google_Service_YouTube($this->client);
        }
    }

    /**
     * @param $playlistId
     * @return \Google_Service_YouTube_PlaylistItem
     */
    public function getPlaylistVideo($playlistId)
    {
        $this->initService();

        return $this->service
            ->playlistItems
            ->listPlaylistItems('snippet', [
                'playlistId' => $playlistId,
                'maxResults' => 50
            ])
            ->getItems();
    }

    /**
     * @param string $searchString
     * @return array
     */
    public function search(string $searchString): array
    {
        $this->initService();

        $searchResponse = $this->service->search->listSearch('id, snippet', [
            'q' => $searchString,
            'maxResults' => 5,
        ]);

        $videos = [];

        foreach ($searchResponse->getItems() as $searchResult) {
            switch ($searchResult['id']['kind']) {
                case 'youtube#video':
                    /**@var \Google_Service_YouTube_SearchResultSnippet $snippet */
                    $snippet = $searchResult['snippet'];
                    $videos[] = [
                        'code' => $searchResult['id']['videoId'],
                        'title' => $snippet->getTitle(),
                        'thumbnail' => $snippet->getThumbnails()->getHigh()->getUrl(),
                    ];
                    break;
            }
        }

        return $videos;
    }
}
