<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pessoas extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'pessoas';
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pai',
        'cadastro_ativo',
        'codigo',
        'nome',
        'alias',
        'email',
        'signature_email',
        'situacao',
        'cpfcnpj',
        'telefone',
        'celular',
        'razao_social',
        'tipo',
        'cpf_responsavel',
        'foto',
        'ie',
        'data_nasc',
        'sexo',
        'rg',
        'orgao_emissor',
        'estado_civil',
        'obs',
        'cod_rec',
        'id_endereco_fiscal',
        'id_clinte',
        'signature_pwd',
        'senha',
        'comiss_elegivel',
        'ccm',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Seleção de emails.
     *
     */
    public function scopeWhereEmail($query, $email)
    {
        return $query->where('email', $email);
    }
     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Muda o campo password(padrão do laravel) para senha.
     *
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
