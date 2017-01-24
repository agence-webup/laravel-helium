<?php

namespace Webup\LaravelHelium\Contact\Contracts;

interface ContactService
{
    /**
     * Get a new query builder for the contat model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();

    /**
     * Get a contact model by its primary key.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById($id);

    /**
     * Create a new contat message.
     *
     * @param  array  $data
     */
    public function create(array $data);

    /**
     * Delete a contact message.
     *
     * @param  mixed  $id
     */
    public function delete($id);
}
