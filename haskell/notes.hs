module Notes where

import Data.List (elemIndex)
import Data.Maybe (fromMaybe)

data Note = A | As | B | C | Cs | D | Ds | E | F | Fs | G | Gs deriving ( Read, Eq, Enum, Show, Ord )

data Scale key = Major key | Minor key | Dominant key deriving (Read, Show)

apply :: (a -> a) -> a -> Int -> a
apply _ a 0 = a
apply f a n = apply f (f a) (n - 1)

next :: Note -> Note
next Gs = A
next n = succ n

prev :: Note -> Note
prev A = Gs
prev n = pred n

whole :: Note -> Note
whole = next . next
half :: Note -> Note
half = next

generateScale :: Scale Note -> [Note]
generatorHelper :: [Int] -> Note -> [Note]
generatorHelper i n = map (apply half (prev n)) i
generateScale (Major key) = generatorHelper [1, 3, 5, 6, 8, 10, 12] key
generateScale (Minor key) = generatorHelper [1, 3, 4, 6, 8, 9, 11] key
generateScale (Dominant key) = generatorHelper [1, 3, 5, 6, 8, 10, 11] key

scaleOfLength :: Scale Note -> Int -> [Note]
scaleOfLength s i
  | length scale >= i = take i scale
  | otherwise = scale ++ scaleOfLength s (i-length scale)
  where scale = generateScale s

indexForNote :: Scale Note -> Note -> Maybe Int
indexForNote s note = do
  n <- elemIndex note $ generateScale s
  Just n

scaleOfLengthWithStartingNote :: Scale Note -> Note -> Int -> Maybe [Note]
scaleOfLengthWithStartingNote s n i = do
  start <- indexForNote s n
  Just $ drop start $ scaleOfLength s $ i + start

fretColumn :: Scale Note -> Note -> Int -> [Int]
fretColumn s n i
  | i == 0 = []
  | otherwise = ( numForNote + 1 ) : fretColumn s (next n) (i-1)
  where numForNote = fromMaybe ( -1 ) $ indexForNote s n
