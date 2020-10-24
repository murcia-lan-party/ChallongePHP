<?php

namespace Reflex\Challonge\DTO;

use Illuminate\Support\Collection;
use Reflex\Challonge\DtoClientTrait;
use Reflex\Challonge\Exceptions\StillRunningException;
use Reflex\Challonge\Exceptions\AlreadyStartedException;
use Spatie\DataTransferObject\DataTransferObject;

class Tournament extends DataTransferObject
{
    use DtoClientTrait;

    public int $id;
    public string $name;
    public string $url;
    public string $description;
    public string $tournament_type;
    public ?string $started_at;
    public ?string $completed_at;
    public bool $require_score_agreement;
    public bool $notify_users_when_matches_open;
    public string $created_at;
    public string $updated_at;
    public string $state;
    public bool $open_signup;
    public bool $notify_users_when_the_tournament_ends;
    public int $progress_meter;
    public bool $quick_advance;
    public bool $hold_third_place_match;
    public string $pts_for_game_win;
    public string $pts_for_game_tie;
    public string $pts_for_match_win;
    public string $pts_for_match_tie;
    public string $pts_for_bye;
    public int $swiss_rounds;
    public bool $private;
    public ?string $ranked_by;
    public bool $show_rounds;
    public bool $hide_forum;
    public bool $sequential_pairings;
    public bool $accept_attachments;
    public string $rr_pts_for_game_win;
    public string $rr_pts_for_game_tie;
    public string $rr_pts_for_match_win;
    public string $rr_pts_for_match_tie;
    public bool $created_by_api;
    public bool $credit_capped;
    public ?int $category;
    public bool $hide_seeds;
    public int $prediction_method;
    public ?string $predictions_opened_at;
    public bool $anonymous_voting;
    public int $max_predictions_per_user;
    public ?int $signup_cap;
    public ?int $game_id;
    public int $participants_count;
    public bool $group_stages_enabled;
    public bool $allow_participant_match_reporting;
    public  $teams;
    public $check_in_duration;
    public ?string $start_at;
    public ?string $started_checking_in_at;
    public $tie_breaks;
    public ?string $locked_at;
    public ?int $event_id;
    public ?bool $public_predictions_before_start_time;
    public $ranked;
    public ?string $grand_finals_modifier;
    public $predict_the_losers_bracket;
    public $spam;
    public $ham;
    public ?int $rr_iterations;
    public ?int $tournament_registration_id;
    public ?bool $donation_contest_enabled;
    public ?bool $mandatory_donation;
    public $non_elimination_tournament_data;
    public ?bool $auto_assign_stations;
    public ?bool $only_start_matches_with_stations;
    public string $registration_fee;
    public string $registration_type;
    public bool $split_participants;
    public ?array $allowed_regions;
    public ?bool $show_participant_country;
    public ?int $program_id;
    public $program_classification_ids_allowed;
    public string $description_source;
    public ?string $subdomain;
    public string $full_challonge_url;
    public string $live_image_url;
    public ?string $sign_up_url;
    public bool $review_before_finalizing;
    public bool $accepting_predictions;
    public bool $participants_locked;
    public ?string $game_name;
    public bool $participants_swappable;
    public bool $team_convertable;
    public bool $group_stages_were_started;

    /**
     * Start a tournament, opening up first round matches for score reporting.
     * @return Tournament
     * @throws AlreadyStartedException
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function start(): Tournament
    {
        if ($this->state !== 'pending') {
            throw new AlreadyStartedException('Tournament is already underway.');
        }

        $response = $this->client->request('post', "tournaments/{$this->id}/start");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Finalize a tournament that has had all match scores submitted, rendering its results permanent.
     * @return Tournament
     * @throws StillRunningException
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function finalize(): Tournament
    {
        if ($this->state !== 'awaiting_review') {
            throw new StillRunningException('Tournament is still running.');
        }

        $response = $this->client->request('post', "tournaments/{$this->id}/finalize");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Reset a tournament, clearing all of its scores and attachments.
     * @return Tournament
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function reset(): Tournament
    {
        $response = $this->client->request('post', "tournaments/{$this->id}/reset");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Update a tournament's attributes.
     * @param array $options
     * @return Tournament
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function update(array $options = []): Tournament
    {
        $response = $this->client->request('put', "tournaments/{$this->id}", $options);
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Deletes a tournament along with all its associated records.
     * @return bool
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function delete(): bool
    {
        $response = $this->client->request('delete', "tournaments/{$this->id}");
        // TODO: validate the response
        return true;
    }

    /**
     * Add a participant to a tournament (up until it is started).
     * @param array $options
     * @return Participant
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function addParticipant(array $options = []): Participant
    {
        $response = $this->client->request('post', "tournaments/{$this->id}/participants", $options);
        return Participant::fromResponse($this->client, $response['participant']);
    }

    /**
     * Bulk add participants to a tournament (up until it is started).
     * @param array $options
     * @return Collection
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function bulkAddParticipant(array $options = []): Collection
    {
        $response = $this->client->request('post', "tournaments/{$this->id}/participants/bulk_add", $options);
        return Collection::make($response)
            ->map(fn (array $participant) => Participant::fromResponse($this->client, $participant['participant']));
    }
}
