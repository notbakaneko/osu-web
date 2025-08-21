<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Libraries;

use Illuminate\Database\Connection;

class TransactionStateManager
{
    private array $states;

    public function __construct()
    {
        $this->resetStates();
    }

    public function isCompleted(): bool
    {
        foreach ($this->states as $_name => $state) {
            if (!$state->isCompleted()) {
                return false;
            }
        }

        return true;
    }

    public function begin(Connection $connection)
    {
        $this->states[$connection->getName()] ??= new TransactionState($connection);
    }

    public function commit(): void
    {
        if ($this->isCompleted()) {
            foreach ($this->states as $_name => $state) {
                $state->commit();
            }
            $this->resetStates();
        }
    }

    public function current(string $name): TransactionState
    {
        return $this->states[$name] ?? $this->states[''];
    }

    public function rollback(): void
    {
        if ($this->isCompleted()) {
            foreach ($this->states as $_name => $state) {
                $state->rollback();
            }
            $this->resetStates();
        }
    }

    private function resetStates(): void
    {
        // for handling cases outside of transactions.
        $this->states = ['' => new TransactionState(null)];
    }
}
