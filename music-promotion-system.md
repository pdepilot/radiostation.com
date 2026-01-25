# Minimalist Music Promotion System (Laravel)

## Purpose
Automated, paid, limited-slot music promotion displayed on the homepage as cards, positioned after Featured Sponsors.

---

## Core Idea
Homepage = fixed number of paid “Music Promotion Slots”.
Scarcity-driven, duration-based, fully automated.

---

## Slot Rules
- MAX_SLOTS = fixed (e.g. 6)
- Only ACTIVE promotions count toward slots
- If slots are full → payment disabled + email waitlist shown

---

## Business Model
- Product: Music Promotion Slot
- Pricing: Flat fee per duration (e.g. 7 / 14 days)
- No subscriptions, no tiers, no manual approval

---

## User Flow (Automated)
1. User clicks “Promote Your Music”
2. System checks available slots
3. If full → show waitlist email form
4. If available → show submission form
5. User pays via Paystack
6. Paystack webhook verifies payment
7. Promotion auto-activates
8. Card appears instantly on homepage
9. Promotion auto-expires via scheduler

---

## Data Models

### music_promotions
- id
- user_id
- artist_name
- track_title
- description
- audio_embed_url
- cover_image
- cta_url
- starts_at
- ends_at
- status (pending | active | expired)
- timestamps

### promotion_payments
- id
- music_promotion_id
- paystack_reference
- amount
- status (pending | success | failed)
- timestamps

### promotion_waitlist
- id
- email
- timestamps

---

## Payment
- Paystack redirect or inline checkout
- No card storage
- Webhook activates promotion on success

---

## Automation
- Laravel scheduler expires promotions daily
- Slot count recalculated automatically
- Waitlist emails sent when slots open

---

## Tracking (Free)
- Google Analytics for impressions & clicks
- Optional DB click counter on CTA

---

## Homepage Display
- Query ACTIVE promotions
- Limit by MAX_SLOTS
- Render as responsive cards
- Order by created_at or ends_at

---

## Admin Role
- View promotions
- View payments
- Configure slot count & pricing
- No manual approvals

---

## Key Principles
- No playlists
- No streaming hosting
- No subscriptions
- No admin bottlenecks
- Minimal UI, minimal logic

Flow: 
Promote Your Music click —> check ACTIVE slots vs MAX_SLOTS —> if full: show waitlist email form —> if available: show submission form —> user selects duration (7 / 14 days) —> system calculates price —> redirect to Paystack checkout —> payment success —> Paystack webhook verifies payment —> create promotion record —> set status = pending —> notify admin (dashboard / email) —> admin reviews content —> admin approves promotion —> set status = active —> set starts_at = approval time —> set ends_at = starts_at + duration —> auto-place card on homepage —> order by price_paid DESC, created_at ASC —> promotion visible to public —> scheduler expires promotion —> status = expired —> slot freed —> notify waitlist








[ Homepage ]
    |
    v
[ 🎵 Promote Your Music Button ]
    |
    v
[ Modal Opens ]
    |
    v
[ Check Available Slots ]
    |
    ├── Slots Full
    |       |
    |       v
    |   [ Email Waitlist Form ]
    |
    └── Slots Available
            |
            v
    [ Music Submission Form ]
            |
            v
    [ Select Duration (7 / 14 days) ]
            |
            v
    [ Paystack Checkout ]
            |
            v
    [ Payment Success ]
            |
            v
    [ Status: PENDING ]
            |
            v
    [ Admin Approval ]
            |
            v
    [ Status: ACTIVE ]
            |
            v
[ Homepage Music Cards Section ]
 (ordered by price DESC, time ASC)
            |
            v
    [ Auto Expiry ]
            |
            v
    [ Slot Freed → Notify Waitlist ]
