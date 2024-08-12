<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WalletTransactionHistory extends Model
{
    use HasFactory;

    protected $table = 'wallet_transaction_history';

    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'transaction_date',
        'status',
        'description',
    ];

    // Define constants for status values
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_FAILED = 3;

    /**
     * Get all transactions with a specific status.
     *
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByStatus($status, $userId = null)
    {
        $query = self::where('status', $status);
    
        if ($userId) {
            $query->where('user_id', $userId);
        }
    
        return $query->get();
    }

    /**
     * Count transactions by status.
     *
     * @param int $status
     * @return int
     */
    public static function countByStatus($status)
    {
        return self::where('status', $status)->count();
    }

    /**
     * Update the status of a transaction by its ID and handle referral points.
     *
     * @param int $id
     * @param int $status
     * @return bool
     */
    public static function updateStatus($id, $status , $point = 0)
    {
        $transaction = self::find($id);

        if ($transaction) {

            $transaction->status = $status;

            if ($status === self::STATUS_COMPLETED) {
           
                self::updateReferralPoints($transaction, $point);
            }

            return $transaction->save();
        }
        return false;
    }

    /**
     * Handle referral points update when a transaction is completed.
     *
     * @param \App\Models\WalletTransactionHistory $transaction
     * @return void
     */
    private static function updateReferralPoints(WalletTransactionHistory $transaction , $points)
    {
        $referrer = User::find($transaction->user_id);

        if ($referrer) {

            $pointsToAdd = $points;

            $referrer->increment('wallet_amount', $pointsToAdd);

        }
    }

    /**
     * Create a new transaction record.
     *
     * @param array $data
     * @return \App\Models\WalletTransactionHistory
     */
    public static function createTransaction(array $data)
    {
        return self::create($data);
    }

    function user() {

        $this->belongsTo(User::class , 'user_id' ,'id');
        
    }
}
