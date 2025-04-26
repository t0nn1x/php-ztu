# Cleanup Expired Reservations Cron Job

This document describes the implementation of the automated cleanup process for expired reservations in the MyHotel system.

## Overview

The system includes a scheduled task that automatically cancels expired reservations that meet specific criteria. This task runs daily at midnight using Symfony's Scheduler component.

## Implementation Details

### Command Class

The cleanup functionality is implemented in `src/Command/CleanupExpiredReservationsCommand.php`. This command:

- Identifies expired reservations based on:
  - Reservation status is "confirmed"
  - Check-out date is in the past
- Changes their status to "canceled"
- Reports the number of processed reservations

```php
#[AsCommand(
    name: 'app:cleanup-expired-reservations',
    description: 'Cleans up expired reservations that were not completed',
)]
class CleanupExpiredReservationsCommand extends Command
{
    // ... implementation details
}
```

### Scheduling Configuration

The command is scheduled in `config/services.yaml`:

```yaml
App\Command\CleanupExpiredReservationsCommand:
    tags:
        - { name: 'console.command' }
        - { name: 'scheduler.recurring_command', expression: '0 0 * * *' } # Run daily at midnight
```

The cron expression `0 0 * * *` means:
- `0` - At minute 0
- `0` - At hour 0 (midnight)
- `*` - Every day of the month
- `*` - Every month
- `*` - Every day of the week

## Setup Requirements

1. Install the Symfony Scheduler component:
```bash
composer require symfony/scheduler
```

2. Ensure the Scheduler transport factory is configured in `services.yaml`:
```yaml
Symfony\Component\Scheduler\Messenger\SchedulerTransportFactory:
    tags: ['messenger.transport_factory']
```

## Running the Scheduler

To start the scheduler worker:
```bash
php bin/console messenger:consume scheduled
```

It's recommended to run this command using a process manager in production to ensure it stays running.

## Manual Execution

For testing or manual execution, run:
```bash
php bin/console app:cleanup-expired-reservations
```

## Monitoring

The command provides output indicating:
- When it starts running
- Number of reservations processed
- Any errors that occurred during execution

## Future Enhancements

Planned enhancements include:
1. Adding payment status verification
2. Sending notifications for canceled reservations
3. Adding detailed logging
4. Adding metrics collection for monitoring

## Troubleshooting

If the cron job isn't running:
1. Verify the scheduler worker is running
2. Check the logs for any errors
3. Ensure the cron expression is correct
4. Verify the command is properly tagged in services.yaml
5. Check that the Symfony Scheduler component is properly installed 
