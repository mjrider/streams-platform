<?php namespace Anomaly\Streams\Platform\Entry;

use Anomaly\Streams\Platform\Entry\Command\SetMetaInformation;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\Event\EntryWasCreated;
use Anomaly\Streams\Platform\Entry\Event\EntryWasDeleted;
use Anomaly\Streams\Platform\Entry\Event\EntryWasSaved;
use Anomaly\Streams\Platform\Entry\Event\EntryWasUpdated;
use Anomaly\Streams\Platform\Model\Event\ModelsWereDeleted;
use Anomaly\Streams\Platform\Model\Event\ModelsWereUpdated;
use Anomaly\Streams\Platform\Support\Observer;

/**
 * Class EntryObserver
 *
 * @link    http://anomaly.is/streams-platform
 * @author  AnomalyLabs, Inc. <hello@anomaly.is>
 * @author  Ryan Thompson <ryan@anomaly.is>
 *
 * @package Anomaly\Streams\Platform\Entry
 */
class EntryObserver extends Observer
{

    /**
     * Run after a record is created.
     *
     * @param EntryInterface $entry
     */
    public function created(EntryInterface $entry)
    {
        $this->events->fire(new EntryWasCreated($entry));

        $entry->flushCache();
    }

    /**
     * Run after a record is updated.
     *
     * @param EntryInterface $entry
     */
    public function updated(EntryInterface $entry)
    {
        $this->events->fire(new EntryWasUpdated($entry));
    }

    /**
     * Before saving an entry touch the
     * meta information.
     *
     * @param  EntryInterface $entry
     * @return bool
     */
    public function saving(EntryInterface $entry)
    {
        $this->commands->dispatch(new SetMetaInformation($entry));
    }

    /**
     * Run after saving a record.
     *
     * @param EntryInterface $entry
     */
    public function saved(EntryInterface $entry)
    {
        $this->events->fire(new EntryWasSaved($entry));

        $entry->flushCache();
    }

    /**
     * Run after multiple entries have been updated.
     *
     * @param EntryInterface $entry
     */
    public function updatedMultiple(EntryInterface $entry)
    {
        $entry->flushCache();

        $this->events->fire(new ModelsWereUpdated($entry));
    }

    /**
     * Run after a record has been deleted.
     *
     * @param EntryInterface $entry
     */
    public function deleted(EntryInterface $entry)
    {
        $this->events->fire(new EntryWasDeleted($entry));

        $entry->flushCache();
    }

    /**
     * Run after entries records have been deleted.
     *
     * @param EntryInterface $entry
     */
    public function deletedMultiple(EntryInterface $entry)
    {
        $entry->flushCache();

        $this->events->fire(new ModelsWereDeleted($entry));
    }
}
